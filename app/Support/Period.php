<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

/** Payroll periods are integers in yyyymm form (e.g. 202610), as in the original system. */
class Period
{
    public static function current(): int
    {
        return (int) DB::table('tbl_company_info')->value('curr_period') ?: (int) date('Ym');
    }

    public static function next(int $p): int
    {
        return $p % 100 === 12 ? ($p - 11 + 100) : $p + 1;
    }

    public static function label(int $p): string
    {
        return date('F Y', mktime(0, 0, 0, $p % 100, 1, intdiv($p, 100)));
    }

    public static function valid(mixed $p): bool
    {
        return is_numeric($p) && strlen((string) $p) === 6 && (int) $p % 100 >= 1 && (int) $p % 100 <= 12;
    }
}
