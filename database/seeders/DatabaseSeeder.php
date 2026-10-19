<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // CHANGE THIS PASSWORD after the first login (menu: Change Password).
        

        if (! DB::table('tbl_company_info')->exists()) {
            DB::table('tbl_company_info')->insert(['company_name' => 'BREB', 'curr_period' => (int) date('Ym')]);
        }

        // Pay codes referenced by the recovered salary-process logic
        foreach ([
            ['01', 'Basic Pay', 'A'], ['02', 'House Rent Allowance', 'A'], ['10', 'GPF/CPF Contribution', 'D'],
            ['29', 'Overtime (Days)', 'A'], ['30', 'Overtime (Hours)', 'A'],
        ] as [$id, $name, $ad]) {
            DB::table('tbl_paycode')->updateOrInsert(['payid' => $id], ['payname' => $name, 'ad' => $ad]);
        }
    }
}
