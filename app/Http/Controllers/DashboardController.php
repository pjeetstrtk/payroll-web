<?php

namespace App\Http\Controllers;

use App\Support\Period;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $period = Period::current();
        $emp = auth()->user()->emp_id;

        return view('dashboard', [
            'period' => $period,
            'totals' => DB::table('vw_salary_details')->where('employeeid', $emp)->where('period', $period)
                ->selectRaw("SUM(CASE WHEN ad='A' THEN amount ELSE 0 END) allowances, SUM(CASE WHEN ad='D' THEN amount ELSE 0 END) deductions")->first(),
            'lines' => DB::table('vw_salary_details')->where('employeeid', $emp)->where('period', $period)->orderBy('ad')->orderBy('payid')->get(['payid', 'payname', 'ad', 'amount']),
            'employees' => DB::table('tbl_employeemaster')->count(),
            'processed' => DB::table('tbl_paytran_master')->where('period', $period)->count(),
        ]);
    }
}
