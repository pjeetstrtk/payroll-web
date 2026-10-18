<?php

/*
 * Declarative definitions of the simple maintenance screens (one generic
 * controller/view serves all of them). Derived from the original .aspx forms.
 */
return [
    'banks' => [
        'title' => 'Banks',
        'table' => 'tbl_bank',
        'pk' => 'bankid',
        'order' => 'bankid',
        'fields' => [
            'bankid' => [
                'label' => 'Bank ID',
                'type' => 'text',
                'required' => true,
                'max' => 10,
                'pk' => true,
            ],
            'bank_description' => [
                'label' => 'Bank Name',
                'type' => 'text',
                'required' => true,
                'max' => 255,
            ],
            'gl_code' => [
                'label' => 'GL Code',
                'type' => 'text',
                'required' => false,
                'max' => 40,
            ],
        ],
    ],
    'bank-branches' => [
        'title' => 'Bank Branches',
        'table' => 'tbl_bank_branch',
        'pk' => 'br_id',
        'order' => 'br_id',
        'fields' => [
            'br_id' => [
                'label' => 'Branch Key (BANKBR_ID)',
                'type' => 'text',
                'required' => true,
                'max' => 20,
                'pk' => true,
            ],
            'bankid' => [
                'label' => 'Bank',
                'type' => 'select',
                'required' => true,
                'options' => [
                    'table' => 'tbl_bank',
                    'value' => 'bankid',
                    'label' => 'bank_description',
                ],
            ],
            'branchid' => [
                'label' => 'Branch ID',
                'type' => 'text',
                'required' => false,
                'max' => 10,
            ],
            'branch_description' => [
                'label' => 'Branch Name',
                'type' => 'text',
                'required' => true,
                'max' => 255,
            ],
        ],
    ],
    'cadres' => [
        'title' => 'Cadre',
        'table' => 'tbl_cadre',
        'pk' => 'cadreid',
        'order' => 'cadreid',
        'fields' => [
            'cadreid' => [
                'label' => 'Cadre ID',
                'type' => 'text',
                'required' => true,
                'max' => 10,
                'pk' => true,
            ],
            'cadre_description' => [
                'label' => 'Description',
                'type' => 'text',
                'required' => true,
                'max' => 255,
            ],
        ],
    ],
    'managements' => [
        'title' => 'Management',
        'table' => 'tbl_management',
        'pk' => 'managementid',
        'order' => 'managementid',
        'fields' => [
            'managementid' => [
                'label' => 'Management ID',
                'type' => 'text',
                'required' => true,
                'max' => 10,
                'pk' => true,
            ],
            'mgmt_description' => [
                'label' => 'Description',
                'type' => 'text',
                'required' => true,
                'max' => 255,
            ],
        ],
    ],
    'designations' => [
        'title' => 'Designation',
        'table' => 'tbl_designation',
        'pk' => 'designationid',
        'order' => 'designationid',
        'fields' => [
            'designationid' => [
                'label' => 'Designation ID',
                'type' => 'text',
                'required' => true,
                'max' => 10,
                'pk' => true,
            ],
            'dsg_description' => [
                'label' => 'Description',
                'type' => 'text',
                'required' => true,
                'max' => 255,
            ],
            'class' => [
                'label' => 'Class',
                'type' => 'text',
                'required' => false,
                'max' => 10,
            ],
            'hourly_dist' => [
                'label' => 'Hourly Dist.',
                'type' => 'text',
                'required' => false,
                'max' => 10,
            ],
            'managementid' => [
                'label' => 'Management',
                'type' => 'select',
                'required' => true,
                'options' => [
                    'table' => 'tbl_management',
                    'value' => 'managementid',
                    'label' => 'mgmt_description',
                ],
            ],
        ],
    ],
    'directorates' => [
        'title' => 'Directorate',
        'table' => 'tbl_directorate',
        'pk' => 'directorateid',
        'order' => 'directorateid',
        'fields' => [
            'directorateid' => [
                'label' => 'Directorate ID',
                'type' => 'text',
                'required' => true,
                'max' => 10,
                'pk' => true,
            ],
            'dir_description' => [
                'label' => 'Description',
                'type' => 'text',
                'required' => true,
                'max' => 255,
            ],
        ],
    ],
    'divcells' => [
        'title' => 'Division / Section / Cell',
        'table' => 'tbl_divisionsectioncell',
        'pk' => 'dir_divcell_id',
        'order' => 'dir_divcell_id',
        'fields' => [
            'dir_divcell_id' => [
                'label' => 'Directorate-Div ID (e.g. 645-0)',
                'type' => 'text',
                'required' => true,
                'max' => 20,
                'pk' => true,
            ],
            'directorateid' => [
                'label' => 'Directorate',
                'type' => 'select',
                'required' => true,
                'options' => [
                    'table' => 'tbl_directorate',
                    'value' => 'directorateid',
                    'label' => 'dir_description',
                ],
            ],
            'divisioncellid' => [
                'label' => 'Division/Cell ID',
                'type' => 'text',
                'required' => true,
                'max' => 10,
            ],
            'divcel_description' => [
                'label' => 'Description',
                'type' => 'text',
                'required' => true,
                'max' => 255,
            ],
        ],
    ],
    'locations' => [
        'title' => 'Location / District',
        'table' => 'tbl_location',
        'pk' => 'locationid',
        'order' => 'locationid',
        'fields' => [
            'locationid' => [
                'label' => 'Location ID',
                'type' => 'text',
                'required' => true,
                'max' => 10,
                'pk' => true,
            ],
            'location_description' => [
                'label' => 'Description',
                'type' => 'text',
                'required' => true,
                'max' => 255,
            ],
            'min_tax' => [
                'label' => 'Minimum Tax',
                'type' => 'number',
                'required' => false,
            ],
        ],
    ],
    'projects' => [
        'title' => 'Revenue Projects',
        'table' => 'tbl_project_revenue',
        'pk' => 'revenueprojectid',
        'order' => 'revenueprojectid',
        'fields' => [
            'revenueprojectid' => [
                'label' => 'Project ID',
                'type' => 'text',
                'required' => true,
                'max' => 10,
                'pk' => true,
            ],
            'revenue_proj_description' => [
                'label' => 'Description',
                'type' => 'text',
                'required' => true,
                'max' => 255,
            ],
        ],
    ],
    'paycodes' => [
        'title' => 'Allowances / Deductions (Pay Codes)',
        'table' => 'tbl_paycode',
        'pk' => 'payid',
        'order' => 'payid',
        'fields' => [
            'payid' => [
                'label' => 'Pay ID',
                'type' => 'text',
                'required' => true,
                'max' => 4,
                'pk' => true,
            ],
            'payname' => [
                'label' => 'Name',
                'type' => 'text',
                'required' => true,
                'max' => 255,
            ],
            'ad' => [
                'label' => 'Type',
                'type' => 'select',
                'required' => true,
                'static' => [
                    'A' => 'Allowance / Earning',
                    'D' => 'Deduction',
                ],
            ],
            'cpr_order' => [
                'label' => 'CPR Order',
                'type' => 'number',
                'required' => false,
            ],
            'gl_code' => [
                'label' => 'GL Code',
                'type' => 'text',
                'required' => false,
                'max' => 40,
            ],
            'pay_name_gl' => [
                'label' => 'GL Name',
                'type' => 'text',
                'required' => false,
                'max' => 255,
            ],
            'adv_int_code' => [
                'label' => 'Interest Pay ID (loans)',
                'type' => 'text',
                'required' => false,
                'max' => 4,
            ],
            'gl_13_20' => [
                'label' => 'GL 13-20',
                'type' => 'text',
                'required' => false,
                'max' => 40,
            ],
        ],
    ],
    'payscale' => [
        'title' => 'Pay Scale (2015)',
        'table' => 'tbl_payscale_2015',
        'pk' => 'id',
        'auto' => true,
        'order' => 'pay_grade, increment_number',
        'fields' => [
            'pay_grade' => [
                'label' => 'Grade',
                'type' => 'number',
                'required' => true,
            ],
            'increment_number' => [
                'label' => 'Increment No.',
                'type' => 'number',
                'required' => true,
            ],
            'basic' => [
                'label' => 'Basic',
                'type' => 'number',
                'required' => true,
            ],
        ],
    ],
    'house-rent' => [
        'title' => 'House Rent Allowance Policy',
        'table' => 'tbl_policy',
        'pk' => 'id',
        'auto' => true,
        'order' => 'location, basic_from',
        'fixed' => [
            'paycode' => '02',
        ],
        'fields' => [
            'location' => [
                'label' => 'Location',
                'type' => 'select',
                'required' => true,
                'options' => [
                    'table' => 'tbl_location',
                    'value' => 'locationid',
                    'label' => 'location_description',
                ],
            ],
            'basic_from' => [
                'label' => 'Basic From',
                'type' => 'number',
                'required' => true,
            ],
            'basic_to' => [
                'label' => 'Basic To',
                'type' => 'number',
                'required' => true,
            ],
            'rate' => [
                'label' => 'Rate %',
                'type' => 'number',
                'required' => true,
            ],
            'min_amount' => [
                'label' => 'Minimum Amount',
                'type' => 'number',
                'required' => false,
            ],
            'eff_period' => [
                'label' => 'Effective Period (yyyymm)',
                'type' => 'number',
                'required' => false,
            ],
        ],
    ],
    'tax-rate' => [
        'title' => 'Income Tax Rate Slabs',
        'table' => 'tbl_policy',
        'pk' => 'id',
        'auto' => true,
        'order' => 'eff_period, fix_amount',
        'fixed' => [
            'paycode' => '101',
        ],
        'fields' => [
            'policy_num' => [
                'label' => 'Policy No.',
                'type' => 'text',
                'required' => false,
                'max' => 20,
            ],
            'fix_amount' => [
                'label' => 'Fixed Amount',
                'type' => 'number',
                'required' => false,
            ],
            'basic_from' => [
                'label' => 'Basic From',
                'type' => 'number',
                'required' => true,
            ],
            'basic_to' => [
                'label' => 'Basic To',
                'type' => 'number',
                'required' => true,
            ],
            'rate' => [
                'label' => 'Rate %',
                'type' => 'number',
                'required' => true,
            ],
            'eff_period' => [
                'label' => 'Effective Period (yyyymm)',
                'type' => 'number',
                'required' => false,
            ],
        ],
    ],
    'cpf-gpf' => [
        'title' => 'CPF / GPF Percentage',
        'table' => 'tbl_cpf_gpf_pct',
        'pk' => 'id',
        'auto' => true,
        'order' => 'employeeid',
        'fields' => [
            'employeeid' => [
                'label' => 'Employee ID',
                'type' => 'text',
                'required' => true,
                'max' => 20,
            ],
            'cpf_gpf_pct' => [
                'label' => 'Percentage',
                'type' => 'number',
                'required' => true,
            ],
            'from_date' => [
                'label' => 'From Date',
                'type' => 'date',
                'required' => false,
            ],
        ],
    ],
    'overtime' => [
        'title' => 'Overtime / Leave Entry',
        'table' => 'tbl_utxmaster',
        'pk' => 'id',
        'auto' => true,
        'order' => 'period desc, employeeid',
        'fields' => [
            'employeeid' => [
                'label' => 'Employee ID',
                'type' => 'text',
                'required' => true,
                'max' => 20,
            ],
            'period' => [
                'label' => 'Period (yyyymm)',
                'type' => 'number',
                'required' => true,
            ],
            'otdays' => [
                'label' => 'OT Days',
                'type' => 'number',
                'required' => false,
            ],
            'otdaysrate' => [
                'label' => 'OT Day Rate',
                'type' => 'number',
                'required' => false,
            ],
            'othrs' => [
                'label' => 'OT Hours',
                'type' => 'number',
                'required' => false,
            ],
            'othrsrate' => [
                'label' => 'OT Hour Rate',
                'type' => 'number',
                'required' => false,
            ],
            'eldays' => [
                'label' => 'Earned Leave Days',
                'type' => 'number',
                'required' => false,
            ],
            'lwp' => [
                'label' => 'LWP Days',
                'type' => 'number',
                'required' => false,
            ],
        ],
    ],
    'special-pay' => [
        'title' => 'Special Allowances & Deductions / Bonus',
        'table' => 'tbl_specialpay_tran',
        'pk' => 'id',
        'auto' => true,
        'order' => 'period desc, employeeid',
        'fields' => [
            'employeeid' => [
                'label' => 'Employee ID',
                'type' => 'text',
                'required' => true,
                'max' => 20,
            ],
            'period' => [
                'label' => 'Period (yyyymm)',
                'type' => 'number',
                'required' => true,
            ],
            'payid' => [
                'label' => 'Pay Code',
                'type' => 'select',
                'required' => true,
                'options' => [
                    'table' => 'tbl_paycode',
                    'value' => 'payid',
                    'label' => 'payname',
                ],
            ],
            'ad' => [
                'label' => 'Type',
                'type' => 'select',
                'required' => true,
                'static' => [
                    'A' => 'Allowance / Earning',
                    'D' => 'Deduction',
                ],
            ],
            'amount' => [
                'label' => 'Amount',
                'type' => 'number',
                'required' => true,
            ],
            'withpayslip' => [
                'label' => 'Show on payslip',
                'type' => 'select',
                'required' => true,
                'static' => [
                    'Y' => 'Yes',
                    'N' => 'No',
                ],
            ],
            'particulars' => [
                'label' => 'Particulars',
                'type' => 'text',
                'required' => false,
                'max' => 255,
            ],
        ],
    ],
    'promotions' => [
        'title' => 'Promotions',
        'table' => 'tbl_promotions',
        'pk' => 'id',
        'auto' => true,
        'order' => 'eff_period desc',
        'fields' => [
            'employee_id' => [
                'label' => 'Employee ID',
                'type' => 'text',
                'required' => true,
                'max' => 20,
            ],
            'eff_period' => [
                'label' => 'Effective Period (yyyymm)',
                'type' => 'number',
                'required' => true,
            ],
            'promote_date' => [
                'label' => 'Promotion Date',
                'type' => 'date',
                'required' => true,
            ],
            'old_grade' => [
                'label' => 'Old Grade',
                'type' => 'number',
                'required' => false,
            ],
            'old_incrm' => [
                'label' => 'Old Increment',
                'type' => 'number',
                'required' => false,
            ],
            'new_grade' => [
                'label' => 'New Grade',
                'type' => 'number',
                'required' => false,
            ],
            'new_incrm' => [
                'label' => 'New Increment',
                'type' => 'number',
                'required' => false,
            ],
        ],
    ],
    'loans' => [
        'title' => 'Loan & Advance Headers',
        'table' => 'tbl_advance_header',
        'pk' => 'loan_ref',
        'order' => 'loandate desc',
        'fields' => [
            'loan_ref' => [
                'label' => 'Loan Ref',
                'type' => 'text',
                'required' => true,
                'max' => 30,
                'pk' => true,
            ],
            'employeeid' => [
                'label' => 'Employee ID',
                'type' => 'text',
                'required' => true,
                'max' => 20,
            ],
            'payid' => [
                'label' => 'Loan Pay Code',
                'type' => 'select',
                'required' => true,
                'options' => [
                    'table' => 'tbl_paycode',
                    'value' => 'payid',
                    'label' => 'payname',
                ],
            ],
            'loandate' => [
                'label' => 'Loan Date',
                'type' => 'date',
                'required' => true,
            ],
            'p_amount' => [
                'label' => 'Principal',
                'type' => 'number',
                'required' => true,
            ],
            'rate_interest' => [
                'label' => 'Interest Rate %',
                'type' => 'number',
                'required' => false,
            ],
            'intinoneinstallment' => [
                'label' => 'Interest per instalment',
                'type' => 'number',
                'required' => false,
            ],
            'p_noofinstallments' => [
                'label' => 'Principal instalments',
                'type' => 'number',
                'required' => true,
            ],
            'i_noofinstallments' => [
                'label' => 'Interest instalments',
                'type' => 'number',
                'required' => false,
            ],
            'starting_date' => [
                'label' => 'Starting Date',
                'type' => 'date',
                'required' => false,
            ],
            'ending_date' => [
                'label' => 'Ending Date',
                'type' => 'date',
                'required' => true,
            ],
        ],
    ],
    'not-paid' => [
        'title' => 'Employees Not Paid This Period',
        'table' => 'tbl_not_paid_emp_by_ymd',
        'pk' => 'id',
        'auto' => true,
        'order' => 'period desc',
        'fields' => [
            'employeeid' => [
                'label' => 'Employee ID',
                'type' => 'text',
                'required' => true,
                'max' => 20,
            ],
            'period' => [
                'label' => 'Period (yyyymm)',
                'type' => 'number',
                'required' => true,
            ],
        ],
    ],
];
