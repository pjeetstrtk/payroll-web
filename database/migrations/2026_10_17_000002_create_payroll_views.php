<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/* MySQL equivalents of the Oracle views the application queried (VW_*). */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE OR REPLACE VIEW vw_division_cell AS
            SELECT dc.dir_divcell_id, dc.dir_divcell_id AS div_cel, dc.directorateid, dc.divisioncellid,
                   dc.divcel_description, d.dir_description
            FROM tbl_divisionsectioncell dc
            LEFT JOIN tbl_directorate d ON d.directorateid = dc.directorateid");

        DB::statement("CREATE OR REPLACE VIEW vw_bank_branch AS
            SELECT b.br_id AS bankbr_id, b.bankid, k.bank_description, b.branchid, b.branch_description, k.gl_code
            FROM tbl_bank_branch b
            LEFT JOIN tbl_bank k ON k.bankid = b.bankid");

        DB::statement("CREATE OR REPLACE VIEW vw_employee_all AS
            SELECT e.*,
                   c.cadre_description, d.dsg_description, d.hourly_dist, d.class, d.managementid,
                   m.mgmt_description,
                   dc.div_cel, dc.directorateid, dc.divisioncellid, dc.dir_description, dc.divcel_description,
                   bb.bank_description, bb.branch_description, bb.bankid
            FROM tbl_employeemaster e
            LEFT JOIN tbl_cadre c ON c.cadreid = e.cadreid
            LEFT JOIN tbl_designation d ON d.designationid = e.designationid
            LEFT JOIN tbl_management m ON m.managementid = d.managementid
            LEFT JOIN vw_division_cell dc ON dc.dir_divcell_id = e.dir_divcell_id
            LEFT JOIN vw_bank_branch bb ON bb.bankbr_id = e.bankbr_id");

        DB::statement("CREATE OR REPLACE VIEW vw_salary_sum AS
            SELECT v.*, m.period, m.paymonth, m.payyear, m.trcv, m.tdeduct
            FROM tbl_paytran_master m
            JOIN vw_employee_all v ON v.employeeid = m.employeeid");

        DB::statement("CREATE OR REPLACE VIEW vw_salary_details AS
            SELECT v.*, p.payname, p.cpr_order, p.pay_name_gl, p.gl_code AS pay_gl_code,
                   d.payid, d.paymonth, d.payyear, d.amount, d.ad, d.arrear,
                   d.jv_glcode, d.remarks, d.rule_no, d.loan_ref_no, d.pay_type, d.period
            FROM tbl_paytran_details d
            JOIN vw_employee_all v ON v.employeeid = d.employeeid
            JOIN tbl_paycode p ON p.payid = d.payid");
    }

    public function down(): void
    {
        foreach (['vw_salary_details', 'vw_salary_sum', 'vw_employee_all', 'vw_bank_branch', 'vw_division_cell'] as $v) {
            DB::statement("DROP VIEW IF EXISTS {$v}");
        }
    }
};
