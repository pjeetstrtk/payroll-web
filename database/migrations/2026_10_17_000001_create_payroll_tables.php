<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * MySQL 8 schema for the BREB payroll system. Table/column names are the
 * lower-case equivalents of the original Oracle objects (MySQL on Linux is
 * case-sensitive for table names). The schema was INFERRED from the .aspx
 * pages, the typed dataset (payDataset.xsd) and the SQL strings compiled into
 * RebPayRollWeb.dll - the original Oracle DDL was not available. Compare it
 * with your real Oracle schema before loading production data.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_company_info', function (Blueprint $t) {
            $t->id();
            $t->string('company_name')->nullable();
            $t->unsignedInteger('curr_period');      // yyyymm
            $t->unsignedInteger('close_period')->nullable();
        });

        Schema::create('tbl_user', function (Blueprint $t) {
            $t->string('emp_id', 20)->primary();
            $t->string('user_name', 100);
            $t->string('user_pass');                 // bcrypt
            $t->string('user_type', 5)->default('USR'); // ADM, MNG, USR
            $t->char('is_active', 1)->default('Y');
        });

        Schema::create('tbl_bank', function (Blueprint $t) {
            $t->string('bankid', 10)->primary();
            $t->string('bank_description');
            $t->string('gl_code', 40)->nullable();
        });
        Schema::create('tbl_bank_branch', function (Blueprint $t) {
            $t->string('br_id', 20)->primary();      // = BANKBR_ID used by employees
            $t->string('bankid', 10)->index();
            $t->string('branchid', 10)->nullable();
            $t->string('branch_description');
        });
        Schema::create('tbl_cadre', function (Blueprint $t) {
            $t->string('cadreid', 10)->primary();
            $t->string('cadre_description');
        });
        Schema::create('tbl_management', function (Blueprint $t) {
            $t->string('managementid', 10)->primary();
            $t->string('mgmt_description');
        });
                                                            Schema::create('tbl_designation', function (Blueprint $t) {
            $t->string('designationid', 10)->primary();
            $t->string('dsg_description');
            $t->string('class', 10)->nullable();
            $t->string('hourly_dist', 10)->nullable();
            $t->string('managementid', 10)->nullable()->index();
        });
        Schema::create('tbl_directorate', function (Blueprint $t) {
            $t->string('directorateid', 10)->primary();
            $t->string('dir_description');
        });
        Schema::create('tbl_divisionsectioncell', function (Blueprint $t) {
            $t->string('dir_divcell_id', 20)->primary();
            $t->string('directorateid', 10)->index();
            $t->string('divisioncellid', 10);
            $t->string('divcel_description');
        });
        Schema::create('tbl_location', function (Blueprint $t) {
            $t->string('locationid', 10)->primary();
            $t->string('location_description');
            $t->decimal('min_tax', 12, 2)->default(0);
        });
        Schema::create('tbl_project_revenue', function (Blueprint $t) {
            $t->string('revenueprojectid', 10)->primary();
            $t->string('revenue_proj_description');
        });
        Schema::create('tbl_paycode', function (Blueprint $t) {
            $t->string('payid', 4)->primary();
            $t->string('payname');
            $t->char('ad', 1)->default('A');         // A = allowance/earning, D = deduction
            $t->unsignedInteger('cpr_order')->nullable();
            $t->string('gl_code', 40)->nullable();
            $t->string('pay_name_gl')->nullable();
            $t->string('adv_int_code', 4)->nullable(); // pay id used for interest on loans
            $t->string('gl_13_20', 40)->nullable();
        });
        Schema::create('tbl_policy', function (Blueprint $t) {  // house rent (02) and income-tax slabs (101)
            $t->id();
            $t->string('policy_num', 20)->nullable();
            $t->string('paycode', 4)->index();
            $t->string('location', 10)->nullable();
            $t->decimal('basic_from', 14, 2)->default(0);
            $t->decimal('basic_to', 14, 2)->default(0);
            $t->decimal('rate', 8, 4)->nullable();
            $t->decimal('fix_amount', 14, 2)->nullable();
            $t->decimal('min_amount', 14, 2)->nullable();
            $t->decimal('all_amt', 14, 2)->nullable();
            $t->unsignedInteger('eff_period')->default(0);
        });
        Schema::create('tbl_payscale_2015', function (Blueprint $t) {
            $t->id();
            $t->unsignedSmallInteger('pay_grade');
            $t->unsignedSmallInteger('increment_number');
            $t->decimal('basic', 14, 2);
            $t->unique(['pay_grade', 'increment_number']);
        });

        Schema::create('tbl_employeemaster', function (Blueprint $t) {
            $t->string('employeeid', 20)->primary();
            $t->string('name');
            $t->string('fathername')->nullable();
            $t->string('sex', 10)->nullable();
            $t->string('m_staus', 10)->nullable();
            $t->string('religion', 20)->nullable();
            $t->string('presentaddress', 500)->nullable();
            $t->string('permanentaddress', 500)->nullable();
            $t->string('district_id', 10)->nullable();
            $t->string('lastdegree', 100)->nullable();
            $t->string('bloodgroup', 10)->nullable();
            $t->string('cpfgpfstatus', 5)->nullable();
            $t->string('locationid', 10)->nullable();
            $t->string('suspendstatus', 5)->default('0');
            $t->string('ebsuspendstatus', 5)->default('0');
            $t->unsignedTinyInteger('incrementstatus')->default(1);
            $t->string('accommodation', 5)->nullable();
            $t->string('employeetypeid', 10)->nullable();
            $t->string('revenueprojectid', 10)->nullable();
            $t->string('designationid', 10)->nullable()->index();
            $t->string('cadreid', 10)->nullable();
            $t->string('chargestatus', 5)->nullable();
            $t->string('accountnumber', 40)->nullable();
            $t->date('joiningdatetopost')->nullable();
            $t->date('retireendcontractdate')->nullable();
            $t->date('dateofbirth')->nullable();
            $t->date('dateoffirstjoining')->nullable();
            $t->date('dateofregularization')->nullable();
            $t->decimal('overtimedailyrate', 12, 2)->nullable();
            $t->decimal('overtimehourlyrate', 12, 2)->nullable();
            $t->unsignedSmallInteger('presentpaygrade')->nullable();
            $t->unsignedSmallInteger('noofincrementspaid')->nullable();
            $t->date('nextincrementduedate')->nullable();
            $t->string('flag', 5)->nullable();
            $t->string('bankbr_id', 20)->nullable();
            $t->string('dir_divcell_id', 20)->nullable()->index();
            $t->string('nid', 30)->nullable();
            $t->string('brid', 20)->nullable();
            $t->string('mob_no', 30)->nullable();
            $t->unsignedSmallInteger('noofincrementspaid_old')->nullable();
            $t->date('nextincrementduedate_old')->nullable();
            $t->decimal('june_15_basic', 14, 2)->nullable();
            $t->string('group_id', 10)->nullable();
            $t->string('pbsac_no', 30)->nullable();
            $t->string('email')->nullable();
            $t->string('tin', 30)->nullable();
            $t->date('child_1_dob')->nullable();
            $t->date('child_2_dob')->nullable();
        });

        Schema::create('tbl_promotions', function (Blueprint $t) {
            $t->id();
            $t->string('employee_id', 20)->index();
            $t->unsignedInteger('eff_period');
            $t->date('promote_date');
            $t->unsignedSmallInteger('old_grade')->nullable();
            $t->unsignedSmallInteger('old_incrm')->nullable();
            $t->unsignedSmallInteger('new_grade')->nullable();
            $t->unsignedSmallInteger('new_incrm')->nullable();
        });
        Schema::create('tbl_increment', function (Blueprint $t) {
            $t->id();
            $t->unsignedInteger('period');
            $t->string('employee_id', 20)->index();
            $t->dateTime('incr_date');
            $t->string('incr_by', 20)->nullable();
            $t->unsignedSmallInteger('prev_grade')->nullable();
            $t->unsignedSmallInteger('prev_incr')->nullable();
            $t->date('prev_incr_date')->nullable();
            $t->unsignedSmallInteger('new_grade')->nullable();
            $t->unsignedSmallInteger('new_incr')->nullable();
            $t->date('new_incr_date')->nullable();
        });
        Schema::create('tbl_cpf_gpf_pct', function (Blueprint $t) {
            $t->id();
            $t->string('employeeid', 20)->index();
            $t->decimal('cpf_gpf_pct', 6, 2)->default(0);
            $t->date('from_date')->nullable();
        });
        Schema::create('tbl_utxmaster', function (Blueprint $t) {  // overtime / earned leave / LWP entries
            $t->id();
            $t->string('employeeid', 20);
            $t->unsignedInteger('period');
            $t->unsignedSmallInteger('payyear')->nullable();
            $t->unsignedTinyInteger('paymonth')->nullable();
            $t->decimal('otdays', 8, 2)->default(0);
            $t->decimal('otdaysrate', 12, 2)->default(0);
            $t->decimal('othrs', 8, 2)->default(0);
            $t->decimal('othrsrate', 12, 2)->default(0);
            $t->decimal('eldays', 8, 2)->default(0);
            $t->decimal('lwp', 8, 2)->default(0);
            $t->index(['employeeid', 'period']);
        });
        Schema::create('tbl_specialpay_tran', function (Blueprint $t) { // SPECIALPAY_SEPERATE_TRAN
            $t->id();
            $t->string('employeeid', 20);
            $t->unsignedInteger('period');
            $t->string('payid', 4);
            $t->char('ad', 1)->default('A');
            $t->decimal('amount', 14, 2);
            $t->char('withpayslip', 1)->default('Y');
            $t->string('particulars')->nullable();
            $t->index(['employeeid', 'period']);
        });
        Schema::create('tbl_not_paid_emp_by_ymd', function (Blueprint $t) {
            $t->id();
            $t->string('employeeid', 20);
            $t->unsignedInteger('period');
            $t->unique(['employeeid', 'period']);
        });

        Schema::create('tbl_emp_last_salary', function (Blueprint $t) { // per-employee template carried month to month
            $t->id();
            $t->unsignedSmallInteger('payyear')->nullable();
            $t->unsignedTinyInteger('paymonth')->nullable();
            $t->string('employeeid', 20)->index();
            $t->string('payid', 4);
            $t->decimal('amount', 14, 2)->default(0);
            $t->char('ad', 1)->default('A');
            $t->unsignedInteger('period')->nullable();
            $t->string('arrear', 5)->default('N');
            $t->string('jv_glcode', 40)->nullable();
            $t->string('remarks')->nullable();
        });
        Schema::create('tbl_paytran_master', function (Blueprint $t) {
            $t->id();
            $t->string('employeeid', 20);
            $t->unsignedSmallInteger('payyear');
            $t->unsignedTinyInteger('paymonth');
            $t->unsignedInteger('period');
            $t->decimal('month_startbasic', 14, 2)->default(0);
            $t->string('name');
            $t->string('locationid', 10)->nullable();
            $t->string('suspendstatus', 5)->nullable();
            $t->string('ebsuspendstatus', 5)->nullable();
            $t->unsignedTinyInteger('incrementstatus')->nullable();
            $t->string('accommodation', 5)->nullable();
            $t->string('employeetypeid', 10)->nullable();
            $t->string('revenueprojectid', 10)->nullable();
            $t->string('designationid', 10)->nullable();
            $t->string('cadreid', 10)->nullable();
            $t->string('chargestatus', 5)->nullable();
            $t->unsignedSmallInteger('presentpaygrade')->nullable();
            $t->unsignedSmallInteger('noofincrementspaid')->nullable();
            $t->date('nextincrementduedate')->nullable();
            $t->string('bankbr_id', 20)->nullable();
            $t->string('dir_divcell_id', 20)->nullable();
            $t->string('accountnumber', 40)->nullable();
            $t->decimal('trcv', 14, 2)->default(0);      // total receipts
            $t->decimal('tdeduct', 14, 2)->default(0);   // total deductions
            $t->unique(['employeeid', 'period']);
        });
        Schema::create('tbl_paytran_details', function (Blueprint $t) {
            $t->id();
            $t->unsignedSmallInteger('payyear');
            $t->unsignedTinyInteger('paymonth');
            $t->string('employeeid', 20);
            $t->string('payid', 4);
            $t->decimal('amount', 14, 2);
            $t->char('ad', 1);
            $t->string('arrear', 5)->default('N');
            $t->string('rule_no', 20)->nullable();
            $t->string('remarks')->nullable();
            $t->string('jv_glcode', 40)->nullable();
            $t->string('jv_rule_no', 20)->nullable();
            $t->string('loan_ref_no', 30)->nullable();
            $t->string('pay_type', 5)->nullable();      // P = loan principal, I = loan interest
            $t->string('src', 5)->default('N');
            $t->unsignedInteger('period');
            $t->index(['employeeid', 'period']);
        });

        Schema::create('tbl_advance_header', function (Blueprint $t) {
            $t->string('loan_ref', 30)->primary();
            $t->string('employeeid', 20)->index();
            $t->string('payid', 4);
            $t->string('account_no', 40)->nullable();
            $t->date('loandate');
            $t->decimal('p_amount', 14, 2);
            $t->decimal('rate_interest', 6, 2)->default(0);
            $t->decimal('intinoneinstallment', 14, 2)->default(0);
            $t->decimal('p_amountpaid', 14, 2)->default(0);
            $t->decimal('i_amountpaid', 14, 2)->default(0);
            $t->decimal('i_accummulated', 14, 2)->default(0);
            $t->unsignedSmallInteger('p_noofinstallments')->default(0);
            $t->unsignedSmallInteger('p_noofinstallmentspaid')->default(0);
            $t->unsignedSmallInteger('i_noofinstallments')->default(0);
            $t->unsignedSmallInteger('i_noofinstallmentspaid')->default(0);
            $t->date('loan_closing_date')->nullable();
            $t->date('int_closing_date')->nullable();
            $t->date('starting_date')->nullable();
            $t->date('ending_date')->nullable();
        });
        Schema::create('tbl_advance_tran', function (Blueprint $t) {
            $t->id();
            $t->string('loan_ref', 30)->index();
            $t->date('trandate')->nullable();
            $t->decimal('amount', 14, 2);
            $t->char('isprinciple', 1);                 // Y = principal, N = interest
            $t->unsignedInteger('period');
            $t->char('othermeans', 1)->default('N');    // A/C = adjustment/cash entries, kept on re-process
        });
        Schema::create('tbl_advance_intaccumm', function (Blueprint $t) {
            $t->id();
            $t->string('loan_ref', 30)->index();
            $t->unsignedInteger('period');
            $t->decimal('amount', 14, 4);
        });
    }

    public function down(): void
    {
        foreach ([
            'tbl_advance_intaccumm', 'tbl_advance_tran', 'tbl_advance_header', 'tbl_paytran_details',
            'tbl_paytran_master', 'tbl_emp_last_salary', 'tbl_not_paid_emp_by_ymd', 'tbl_specialpay_tran',
            'tbl_utxmaster', 'tbl_cpf_gpf_pct', 'tbl_increment', 'tbl_promotions', 'tbl_employeemaster',
            'tbl_payscale_2015', 'tbl_policy', 'tbl_paycode', 'tbl_project_revenue', 'tbl_location',
            'tbl_divisionsectioncell', 'tbl_directorate', 'tbl_designation', 'tbl_management', 'tbl_cadre',
            'tbl_bank_branch', 'tbl_bank', 'tbl_user', 'tbl_company_info',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
