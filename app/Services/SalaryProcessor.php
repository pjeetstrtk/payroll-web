<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Monthly salary calculation for one employee.
 *
 * RECONSTRUCTED from the compiled SalaryProcess class of the original DLL. The
 * order of steps, the tables/columns used and the pay-code ids (01, 02, 10, 29,
 * 30 ...) come from that assembly. Some arithmetic could not be recovered and
 * is marked VERIFY - check these against real payslips before going live.
 */
class SalaryProcessor
{
    private int $year;
    private int $month;
    private int $days;
    private array $warnings = [];

    public function __construct(private int $period)
    {
        $this->year = intdiv($period, 100);
        $this->month = $period % 100;
        $this->days = Carbon::create($this->year, $this->month, 1)->daysInMonth;
    }

    public function warnings(): array
    {
        return $this->warnings;
    }

    /** @return array{ok:bool,message:string} */
    public function process(string $employeeId): array
    {
        $emp = DB::table('vw_employee_all')->where('employeeid', $employeeId)->first();
        if (! $emp) {
            return ['ok' => false, 'message' => "Employee {$employeeId} not found"];
        }
        if (DB::table('tbl_not_paid_emp_by_ymd')->where('employeeid', $employeeId)->where('period', $this->period)->exists()) {
            return ['ok' => true, 'message' => 'Marked as not paid for this period - skipped'];
        }
        if ($emp->retireendcontractdate && (int) Carbon::parse($emp->retireendcontractdate)->format('Ym') < $this->period) {
            return ['ok' => true, 'message' => 'Retired / contract ended before this period - skipped'];
        }

        // 1. wipe previous run of this period for this employee
        DB::table('tbl_paytran_details')->where('period', $this->period)->where('employeeid', $employeeId)->delete();
        DB::table('tbl_paytran_master')->where('period', $this->period)->where('employeeid', $employeeId)->delete();

        $pay = config('payroll.pay');
        $lines = [];

        // 2. basic from pay scale (with promotion split inside the month)
        $basic = $this->scaleBasic($emp->presentpaygrade, $emp->noofincrementspaid);
        if ($basic === null) {
            return ['ok' => false, 'message' => 'Grade Basic Error: no pay-scale row for grade '.$emp->presentpaygrade.' / increment '.$emp->noofincrementspaid];
        }
        $startBasic = $basic;
        $promo = DB::table('tbl_promotions')->where('employee_id', $employeeId)->where('eff_period', $this->period)->first();
        if ($promo && $promo->promote_date) {
            $prev = $this->scaleBasic($promo->old_grade, $promo->old_incrm);
            $day = (int) Carbon::parse($promo->promote_date)->format('j');
            if ($prev !== null && $day > 1) {
                // VERIFY: day-weighted split between old and new basic
                $basic = round($prev * ($day - 1) / $this->days + $basic * ($this->days - $day + 1) / $this->days, 2);
                $startBasic = $prev;
            }
        }
        $lines[] = $this->line($pay['basic'], 'A', $basic);

        // 3. house rent from TBL_POLICY (paycode 02) by location and basic range
        $hr = DB::table('tbl_policy')->where('paycode', config('payroll.policy.house_rent'))
            ->where('location', $emp->locationid)->where('basic_from', '<=', $basic)->where('basic_to', '>=', $basic)
            ->orderByDesc('eff_period')->first();
        if ($hr) {
            // VERIFY: rate % of basic, never below MIN_AMOUNT
            $lines[] = $this->line($pay['house_rent'], 'A', max(round($basic * (float) $hr->rate / 100, 2), (float) $hr->min_amount));
        }

        // 4. overtime (UTX master): pay ids 29 (days) and 30 (hours)
        $utx = DB::table('tbl_utxmaster')->where('employeeid', $employeeId)->where('period', $this->period)
            ->orderByDesc('payyear')->orderByDesc('paymonth')->first();
        if ($utx) {
            if ($utx->otdays > 0) {
                $lines[] = $this->line($pay['ot_days'], 'A', round($utx->otdays * $utx->otdaysrate, 2));
            }
            if ($utx->othrs > 0) {
                $lines[] = $this->line($pay['ot_hours'], 'A', round($utx->othrs * $utx->othrsrate, 2));
            }
            if ($utx->lwp > 0 || $utx->eldays > 0) {
                $this->warnings[] = "{$employeeId}: leave-without-pay / earned-leave days present but the LWP deduction formula could not be recovered - not applied";
            }
        }

        // 5. CPF / GPF contribution
        $pct = DB::table('tbl_cpf_gpf_pct')->where('employeeid', $employeeId)->orderByDesc('from_date')->first();
        if ($pct && $pct->cpf_gpf_pct > 0) {
            $lines[] = $this->line($pay['cpf_gpf'], 'D', round($basic * $pct->cpf_gpf_pct / 100, 2), 'CPF/GPF '.$pct->cpf_gpf_pct.'%');
        }

        // 6. configured allowances / deductions carried from last month's template
        $skip = [$pay['basic'], $pay['house_rent'], $pay['cpf_gpf'], $pay['ot_days'], $pay['ot_hours']];
        foreach (DB::table('tbl_emp_last_salary')->where('employeeid', $employeeId)->whereNotIn('payid', $skip)->get() as $row) {
            if ($row->amount != 0) {
                $lines[] = $this->line($row->payid, $row->ad, (float) $row->amount, $row->remarks, $row->jv_glcode);
            }
        }

        // 7. one-off special pay / festival bonus lines for this period
        foreach (DB::table('tbl_specialpay_tran')->where('employeeid', $employeeId)->where('period', $this->period)
            ->where(fn ($q) => $q->where('withpayslip', 'Y')->orWhere('ad', 'D'))->get() as $row) {
            $lines[] = $this->line($row->payid, $row->ad, (float) $row->amount, $row->particulars, null, null, null, 'S');
        }

        // 8. loans & advances
        foreach ($this->loanLines($employeeId) as $l) {
            $lines[] = $l;
        }

        // 9. persist
        $rcv = 0.0;
        $ded = 0.0;
        foreach ($lines as $l) {
            DB::table('tbl_paytran_details')->insert([
                'payyear' => $this->year, 'paymonth' => $this->month, 'employeeid' => $employeeId,
                'payid' => $l['payid'], 'amount' => $l['amount'], 'ad' => $l['ad'], 'arrear' => $l['arrear'],
                'rule_no' => $l['rule_no'], 'remarks' => $l['remarks'], 'jv_glcode' => $l['jv_glcode'],
                'loan_ref_no' => $l['loan_ref_no'], 'pay_type' => $l['pay_type'], 'src' => $l['src'], 'period' => $this->period,
            ]);
            $l['ad'] === 'A' ? $rcv += $l['amount'] : $ded += $l['amount'];
        }

        DB::table('tbl_paytran_master')->insert([
            'employeeid' => $employeeId, 'payyear' => $this->year, 'paymonth' => $this->month, 'period' => $this->period,
            'month_startbasic' => $startBasic, 'name' => $emp->name, 'locationid' => $emp->locationid,
            'suspendstatus' => $emp->suspendstatus, 'ebsuspendstatus' => $emp->ebsuspendstatus,
            'incrementstatus' => $emp->incrementstatus, 'accommodation' => $emp->accommodation,
            'employeetypeid' => $emp->employeetypeid, 'revenueprojectid' => $emp->revenueprojectid,
            'designationid' => $emp->designationid, 'cadreid' => $emp->cadreid, 'chargestatus' => $emp->chargestatus,
            'presentpaygrade' => $emp->presentpaygrade, 'noofincrementspaid' => $emp->noofincrementspaid,
            'nextincrementduedate' => $emp->nextincrementduedate, 'bankbr_id' => $emp->bankbr_id,
            'dir_divcell_id' => $emp->dir_divcell_id, 'accountnumber' => $emp->accountnumber,
            'trcv' => round($rcv, 2), 'tdeduct' => round($ded, 2),
        ]);

        return ['ok' => true, 'message' => sprintf('Processed: earnings %s, deductions %s, net %s', number_format($rcv, 2), number_format($ded, 2), number_format($rcv - $ded, 2))];
    }

    private function loanLines(string $employeeId): array
    {
        $out = [];
        $headers = DB::table('tbl_advance_header as h')->join('tbl_paycode as p', 'p.payid', '=', 'h.payid')
            ->where('h.employeeid', $employeeId)
            ->whereRaw("DATE_FORMAT(h.loandate, '%Y%m') <= ?", [$this->period])
            ->whereRaw("DATE_FORMAT(h.ending_date, '%Y%m') >= ?", [$this->period])
            ->select('h.*', 'p.adv_int_code')->get();

        foreach ($headers as $h) {
            // clear this period's automatic entries (adjustment 'A'/'C' entries are kept)
            DB::table('tbl_advance_tran')->where('loan_ref', $h->loan_ref)->where('period', $this->period)->whereNotIn('othermeans', ['A', 'C'])->delete();

            $paid = (float) DB::table('tbl_advance_tran')->where('loan_ref', $h->loan_ref)->where('isprinciple', 'Y')->where('period', '<', $this->period)->sum('amount');
            $remaining = round((float) $h->p_amount - $paid, 2);
            if ($remaining > 0 && $h->p_noofinstallments > 0) {
                // VERIFY: equal principal instalments, last one takes the remainder
                $inst = min(round($h->p_amount / $h->p_noofinstallments, 2), $remaining);
                $out[] = $this->line($h->payid, 'D', $inst, 'Loan Deduction of loan#'.$h->loan_ref, null, $h->loan_ref, 'P');
                DB::table('tbl_advance_tran')->insert(['loan_ref' => $h->loan_ref, 'trandate' => Carbon::create($this->year, $this->month, 1)->endOfMonth()->toDateString(), 'amount' => $inst, 'isprinciple' => 'Y', 'period' => $this->period, 'othermeans' => 'N']);
            }
            $intPaidCount = DB::table('tbl_advance_tran')->where('loan_ref', $h->loan_ref)->where('isprinciple', 'N')->where('period', '<', $this->period)->count();
            if ($h->intinoneinstallment > 0 && $h->i_noofinstallments > $intPaidCount && $h->adv_int_code) {
                // VERIFY: interest taken as the fixed INTINONEINSTALLMENT amount
                $out[] = $this->line($h->adv_int_code, 'D', (float) $h->intinoneinstallment, 'Loan Int. Deduction of loan#'.$h->loan_ref, null, $h->loan_ref, 'I');
                DB::table('tbl_advance_tran')->insert(['loan_ref' => $h->loan_ref, 'trandate' => Carbon::create($this->year, $this->month, 1)->endOfMonth()->toDateString(), 'amount' => $h->intinoneinstallment, 'isprinciple' => 'N', 'period' => $this->period, 'othermeans' => 'N']);
            }
            DB::table('tbl_advance_header')->where('loan_ref', $h->loan_ref)->update([
                'p_amountpaid' => DB::table('tbl_advance_tran')->where('loan_ref', $h->loan_ref)->where('isprinciple', 'Y')->sum('amount'),
                'p_noofinstallmentspaid' => DB::table('tbl_advance_tran')->where('loan_ref', $h->loan_ref)->where('isprinciple', 'Y')->count(),
                'i_amountpaid' => DB::table('tbl_advance_tran')->where('loan_ref', $h->loan_ref)->where('isprinciple', 'N')->sum('amount'),
                'i_noofinstallmentspaid' => DB::table('tbl_advance_tran')->where('loan_ref', $h->loan_ref)->where('isprinciple', 'N')->count(),
            ]);
        }

        return $out;
    }

    private function scaleBasic(mixed $grade, mixed $incr): ?float
    {
        if ($grade === null || $incr === null) {
            return null;
        }
        $v = DB::table('tbl_payscale_2015')->where('pay_grade', $grade)->where('increment_number', $incr)->value('basic');

        return $v === null ? null : (float) $v;
    }

    private function line(string $payid, string $ad, float $amount, ?string $remarks = null, ?string $gl = null, ?string $loan = null, ?string $type = null, string $src = 'N'): array
    {
        return ['payid' => $payid, 'ad' => $ad, 'amount' => round($amount, 2), 'arrear' => 'N', 'rule_no' => null,
                'remarks' => $remarks, 'jv_glcode' => $gl, 'loan_ref_no' => $loan, 'pay_type' => $type, 'src' => $src];
    }
}
