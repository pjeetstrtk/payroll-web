<?php

/*
 * Values that were hard-coded inside the original RebPayRollWeb.dll (recovered
 * from the compiled assembly). Change them here instead of in code.
 */
return [
    // Pay-code ids used by the original SalaryProcess class
    'pay' => [
        'basic'        => '01',
        'house_rent'   => '02',
        'cpf_gpf'      => '10',
        'ot_days'      => '29',
        'ot_hours'     => '30',
    ],
    // TBL_POLICY.PAYCODE values
    'policy' => ['house_rent' => '02', 'income_tax' => '101'],
    // Balancing (net payable) line on the Journal Voucher report
    'jv_balance_gl'   => '1421-01-000-000-00-0',
    'jv_balance_name' => 'REB Revenue     CA19072',
    // Roles in TBL_USER.USER_TYPE that may maintain master data / run payroll
    'admin_roles' => ['ADM', 'MNG'],
];
