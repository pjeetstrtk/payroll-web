<?php

namespace App\Http\Controllers;

use App\Services\SalaryProcessor;
use App\Support\Period;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalaryController extends Controller
{
    /** Employees still to be processed in the period (same rule as the original Month Close screen). */
    private function unprocessed(int $period)
    {
        return DB::table('vw_employee_all')
            ->where(fn ($q) => $q->whereRaw("DATE_FORMAT(retireendcontractdate, '%Y%m') >= ?", [$period])->orWhereNull('retireendcontractdate'))
            ->whereNotIn('employeeid', DB::table('tbl_paytran_master')->where('period', $period)->select('employeeid'))
            ->whereNotIn('employeeid', DB::table('tbl_not_paid_emp_by_ymd')->where('period', $period)->select('employeeid'))
            ->orderBy('dir_divcell_id')->orderBy('employeeid');
    }

    public function process()
    {
        $p = Period::current();

        return view('salary.process', [
            'period' => $p, 'pending' => $this->unprocessed($p)->count(),
            'directorates' => DB::table('tbl_directorate')->orderBy('directorateid')->pluck('dir_description', 'directorateid'),
            'result' => session('result'),
        ]);
    }

    public function run(Request $request)
    {
        $data = $request->validate(['scope' => 'required|in:all,directorate,employee', 'directorate' => 'nullable|string', 'employee' => 'nullable|string']);
        $p = Period::current();

        $q = DB::table('tbl_employeemaster')->select('employeeid');
        if ($data['scope'] === 'employee') {
            $q->where('employeeid', $data['employee'] ?? '');
        } elseif ($data['scope'] === 'directorate') {
            $q->whereIn('dir_divcell_id', DB::table('tbl_divisionsectioncell')->where('directorateid', $data['directorate'] ?? '')->select('dir_divcell_id'));
        }

        $proc = new SalaryProcessor($p);
        $ok = 0;
        $errors = [];
        foreach ($q->pluck('employeeid') as $id) {
            try {
                DB::transaction(function () use ($proc, $id, &$ok, &$errors) {
                    $r = $proc->process($id);
                    $r['ok'] ? $ok++ : $errors[] = $id.': '.$r['message'];
                    if (! $r['ok']) {
                        throw new \RuntimeException($r['message']);
                    }
                });
            } catch (\Throwable $e) {
                if (! in_array($id.': '.$e->getMessage(), $errors, true)) {
                    $errors[] = $id.': '.$e->getMessage();
                }
            }
        }

        return redirect()->route('salary.process')->with('result', ['ok' => $ok, 'errors' => $errors, 'warnings' => $proc->warnings()]);
    }

    public function close()
    {
        $p = Period::current();

        return view('salary.close', ['period' => $p, 'next' => Period::next($p), 'pending' => $this->unprocessed($p)->limit(200)->get(), 'count' => $this->unprocessed($p)->count()]);
    }

    /**
     * Month close. Reconstructed from frmMonthClose: roll the template (TBL_EMP_LAST_SALARY) forward,
     * apply the annual increment when the June period is closed, then move CURR_PERIOD forward.
     */
    public function doClose(Request $request)
    {
        $p = Period::current();
        $next = Period::next($p);

        DB::transaction(function () use ($p, $next, $request) {
            DB::table('tbl_emp_last_salary')->delete();
            DB::statement("INSERT INTO tbl_emp_last_salary (payyear, paymonth, employeeid, payid, amount, ad, period, arrear, jv_glcode, remarks)
                SELECT ?, ?, employeeid, payid, amount, ad, ?, 'N', jv_glcode, remarks
                FROM tbl_paytran_details WHERE period = ? AND pay_type IS NULL AND src = 'N'", [intdiv($next, 100), $next % 100, $next, $p]);

            if ($p % 100 === 6) { // VERIFY: increments are applied on closing June
                $max = DB::table('tbl_payscale_2015')->selectRaw('pay_grade, MAX(increment_number) inc')->groupBy('pay_grade')->pluck('inc', 'pay_grade');
                foreach (DB::table('tbl_employeemaster')->where('incrementstatus', 1)->where('presentpaygrade', '>', 0)->get() as $e) {
                    $cap = (int) ($max[$e->presentpaygrade] ?? $e->noofincrementspaid);
                    $new = min(((int) $e->noofincrementspaid) + 1, $cap);
                    $due = $e->nextincrementduedate ? Carbon::parse($e->nextincrementduedate)->addYear()->toDateString() : null;
                    DB::table('tbl_increment')->where('employee_id', $e->employeeid)->where('period', $p)->delete();
                    DB::table('tbl_increment')->insert(['period' => $p, 'employee_id' => $e->employeeid, 'incr_date' => now(), 'incr_by' => $request->user()->emp_id,
                        'prev_grade' => $e->presentpaygrade, 'prev_incr' => $e->noofincrementspaid, 'prev_incr_date' => $e->nextincrementduedate,
                        'new_grade' => $e->presentpaygrade, 'new_incr' => $new, 'new_incr_date' => $due]);
                    DB::table('tbl_employeemaster')->where('employeeid', $e->employeeid)->update(['noofincrementspaid' => $new, 'nextincrementduedate' => $due]);
                }
            }
            DB::table('tbl_company_info')->update(['curr_period' => $next, 'close_period' => $p]);
        });

        return redirect()->route('dashboard')->with('status', 'Period '.Period::label($p).' closed. Current period is now '.Period::label($next).'.');
    }
}
