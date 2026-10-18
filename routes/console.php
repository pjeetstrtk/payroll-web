<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

// The original application stored TBL_USER.USER_PASS in clear text.
// After importing users, run this once to convert them to bcrypt hashes.
Artisan::command('payroll:hash-passwords', function () {
    $n = 0;
    foreach (DB::table('tbl_user')->get() as $u) {
        if (! str_starts_with((string) $u->user_pass, '$2y$')) {
            DB::table('tbl_user')->where('emp_id', $u->emp_id)->update(['user_pass' => Hash::make((string) $u->user_pass)]);
            $n++;
        }
    }
    $this->info("Hashed {$n} password(s).");
})->purpose('Convert clear-text imported passwords to bcrypt');
