<?php

namespace App\Http\Controllers;

use App\Support\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * HTML/print versions of the Crystal Reports. The .rpt layouts were compiled into the
 * original DLL and cannot be recovered, so these are re-designed from the data each report used.
 */
class ReportController extends Controller
{
    private function period(Request $r): int
    {
        $p = $r->query('period', Period::current());

        return Period::valid($p) ? (int) $p : Period::current();
    }

    public function payslip(Request $request)
    {
        $user = $request->user();
        $period = $this->period($request);
        $empId = $user->isAdmin() ? $request->query('employee') : $user->emp_id; // staff only see their own slip
        $emp = $empId ? DB::table('vw_salary_sum')->where('employeeid', $empId)->where('period', $period)->first() : null;
        $lines = $emp ? DB::table('vw_salary_details')->where('employeeid', $empId)->where('period', $period)->orderBy('ad')->orderBy('payid')->get() : collect();

        return view('reports.payslip', compact('emp', 'lines', 'period', 'empId'));
    }

    public function summary(Request $request)
    {
        $period = $this->period($request);
        $rows = DB::table('vw_salary_sum')->where('period', $period)
            ->selectRaw('dir_description, COUNT(*) employees, SUM(trcv) earnings, SUM(tdeduct) deductions, SUM(trcv - tdeduct) net')
            ->groupBy('dir_description')->orderBy('dir_description')->get();

        return view('reports.summary', compact('rows', 'period'));
    }

    public function bankAdvice(Request $request)
    {
        $period = $this->period($request);
        $rows = DB::table('vw_salary_sum')->where('period', $period)->orderBy('bank_description')->orderBy('branch_description')->orderBy('employeeid')
            ->get(['employeeid', 'name', 'accountnumber', 'bank_description', 'branch_description', 'trcv', 'tdeduct']);

        return view('reports.bankadvice', compact('rows', 'period'));
    }

    /** Journal voucher: pay-code totals by GL code (debit = earnings, credit = deductions) plus the balancing payable line. */
    public function jv(Request $request)
    {
        $period = $this->period($request);
        $rows = DB::table('vw_salary_details')->where('period', $period)
            ->selectRaw("COALESCE(NULLIF(jv_glcode,''), pay_gl_code) AS gl, pay_name_gl, payname, SUM(CASE WHEN ad='A' THEN amount ELSE 0 END) AS debit, SUM(CASE WHEN ad='D' THEN amount ELSE 0 END) AS credit")
            ->groupBy('gl', 'pay_name_gl', 'payname')->orderBy('gl')->get();
        $debit = $rows->sum('debit');
        $credit = $rows->sum('credit');

        return view('reports.jv', ['rows' => $rows, 'period' => $period, 'debit' => $debit, 'credit' => $credit, 'net' => $debit - $credit]);
    }
}
