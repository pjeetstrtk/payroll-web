-- ============================================================
-- REBPAYROLL - DEMO DATA SCRIPT (PostgreSQL)
-- TRUNCATE + INSERT for all base tables
-- ============================================================

BEGIN;

-- ------------------------------------------------------------
-- 1. migrations
-- ------------------------------------------------------------
TRUNCATE TABLE migrations RESTART IDENTITY CASCADE;
INSERT INTO migrations (id, migration, batch) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2024_01_01_000001_create_payroll_tables', 1);

-- ------------------------------------------------------------
-- 2. tbl_bank
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_bank RESTART IDENTITY CASCADE;
INSERT INTO tbl_bank (bankid, bank_description, gl_code) VALUES
('SBL', 'Sonali Bank Limited', '1101001'),
('ABL', 'Agrani Bank Limited', '1101002'),
('JBL', 'Janata Bank Limited', '1101003'),
('RBL', 'Rupali Bank Limited', '1101004'),
('PBL', 'Pubali Bank Limited', '1101005');

-- ------------------------------------------------------------
-- 3. tbl_bank_branch
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_bank_branch RESTART IDENTITY CASCADE;
INSERT INTO tbl_bank_branch (br_id, bankid, branchid, branch_description) VALUES
('SBL-DHK01', 'SBL', 'DHK01', 'Sonali Bank - Motijheel Corporate'),
('SBL-DHK02', 'SBL', 'DHK02', 'Sonali Bank - Gulshan Branch'),
('ABL-DHK01', 'ABL', 'DHK01', 'Agrani Bank - Principal Branch'),
('JBL-CTG01', 'JBL', 'CTG01', 'Janata Bank - Agrabad Branch'),
('RBL-RAJ01', 'RBL', 'RAJ01', 'Rupali Bank - Rajshahi Branch');

-- ------------------------------------------------------------
-- 4. tbl_cadre
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_cadre RESTART IDENTITY CASCADE;
INSERT INTO tbl_cadre (cadreid, cadre_description) VALUES
('ADM', 'Administration Cadre'),
('ENG', 'Engineering Cadre'),
('ACC', 'Accounts Cadre'),
('TEC', 'Technical Cadre'),
('GEN', 'General Cadre');

-- ------------------------------------------------------------
-- 5. tbl_management
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_management RESTART IDENTITY CASCADE;
INSERT INTO tbl_management (managementid, mgmt_description) VALUES
('MGMT01', 'Senior Management'),
('MGMT02', 'Mid-Level Management'),
('MGMT03', 'Junior Management'),
('MGMT04', 'Supervisory Level'),
('MGMT05', 'Operational Level');

-- ------------------------------------------------------------
-- 6. tbl_designation
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_designation RESTART IDENTITY CASCADE;
INSERT INTO tbl_designation (designationid, dsg_description, class, hourly_dist, managementid) VALUES
('DSG01', 'Managing Director', '1', 'N', 'MGMT01'),
('DSG02', 'Director (Admin)', '1', 'N', 'MGMT01'),
('DSG03', 'Chief Engineer', '1', 'N', 'MGMT01'),
('DSG04', 'Deputy Director', '2', 'N', 'MGMT02'),
('DSG05', 'Assistant Engineer', '3', 'Y', 'MGMT03'),
('DSG06', 'Accounts Officer', '3', 'N', 'MGMT03'),
('DSG07', 'Junior Assistant', '4', 'Y', 'MGMT05'),
('DSG08', 'Office Assistant', '4', 'Y', 'MGMT05');

-- ------------------------------------------------------------
-- 7. tbl_directorate
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_directorate RESTART IDENTITY CASCADE;
INSERT INTO tbl_directorate (directorateid, dir_description) VALUES
('DIR01', 'Directorate of Administration'),
('DIR02', 'Directorate of Engineering'),
('DIR03', 'Directorate of Finance & Accounts'),
('DIR04', 'Directorate of Planning'),
('DIR05', 'Directorate of Human Resources');

-- ------------------------------------------------------------
-- 8. tbl_divisionsectioncell
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_divisionsectioncell RESTART IDENTITY CASCADE;
INSERT INTO tbl_divisionsectioncell (dir_divcell_id, directorateid, divisioncellid, divcel_description) VALUES
('DIR01-DIV01', 'DIR01', 'DIV01', 'Admin Division - HQ'),
('DIR01-DIV02', 'DIR01', 'DIV02', 'Establishment Cell'),
('DIR02-DIV01', 'DIR02', 'DIV01', 'Civil Engineering Division'),
('DIR02-DIV02', 'DIR02', 'DIV02', 'Electrical Engineering Division'),
('DIR03-DIV01', 'DIR03', 'DIV01', 'Accounts Division'),
('DIR03-DIV02', 'DIR03', 'DIV02', 'Audit Cell'),
('DIR05-DIV01', 'DIR05', 'DIV01', 'HR & Training Cell');

-- ------------------------------------------------------------
-- 9. tbl_location
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_location RESTART IDENTITY CASCADE;
INSERT INTO tbl_location (locationid, location_description, min_tax) VALUES
('LOC01', 'Dhaka Head Office', 0.00),
('LOC02', 'Chittagong Regional Office', 0.00),
('LOC03', 'Rajshahi Zonal Office', 0.00),
('LOC04', 'Khulna Zonal Office', 0.00),
('LOC05', 'Sylhet Zonal Office', 0.00);

-- ------------------------------------------------------------
-- 10. tbl_project_revenue
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_project_revenue RESTART IDENTITY CASCADE;
INSERT INTO tbl_project_revenue (revenueprojectid, revenue_proj_description) VALUES
('REV01', 'Rural Electrification Project'),
('REV02', 'Grid Expansion Project'),
('REV03', 'Smart Metering Project'),
('REV04', 'Renewable Energy Project'),
('REV05', 'General Administration');

-- ------------------------------------------------------------
-- 11. tbl_company_info
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_company_info RESTART IDENTITY CASCADE;
INSERT INTO tbl_company_info (id, company_name, curr_period, close_period) VALUES
(1, 'Rural Electrification Board (REB)', 202510, 202509);

-- ------------------------------------------------------------
-- 12. tbl_paycode
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_paycode RESTART IDENTITY CASCADE;
INSERT INTO tbl_paycode (payid, payname, ad, cpr_order, gl_code, pay_name_gl, adv_int_code, gl_13_20) VALUES
('1001', 'Basic Pay', 'A', 1, '5101001', 'Basic Salary', NULL, NULL),
('1002', 'House Rent Allowance', 'A', 2, '5101002', 'HRA', NULL, NULL),
('1003', 'Medical Allowance', 'A', 3, '5101003', 'Medical Allow.', NULL, NULL),
('1004', 'Conveyance Allowance', 'A', 4, '5101004', 'Conveyance', NULL, NULL),
('1005', 'Dearness Allowance', 'A', 5, '5101005', 'DA', NULL, NULL),
('2001', 'Provident Fund (CPF/GPF)', 'D', 10, '2101001', 'PF Deduction', NULL, NULL),
('2002', 'Income Tax', 'D', 11, '2101002', 'Tax Deduction', NULL, NULL),
('2003', 'Advance / Loan Recovery', 'D', 12, '2101003', 'Loan Recovery', '2004', NULL),
('2004', 'Advance Interest', 'D', 13, '2101004', 'Interest on Advance', NULL, NULL),
('2005', 'Welfare Fund', 'D', 14, '2101005', 'Welfare Deduction', NULL, NULL),
('3001', 'Overtime Pay', 'A', 6, '5101006', 'OT Allowance', NULL, NULL),
('3002', 'Arrear Basic', 'A', 7, '5101007', 'Arrear Basic', NULL, NULL);

-- ------------------------------------------------------------
-- 13. tbl_payscale_2015
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_payscale_2015 RESTART IDENTITY CASCADE;
INSERT INTO tbl_payscale_2015 (id, pay_grade, increment_number, basic) VALUES
(1, 1, 0, 78000.00),
(2, 1, 1, 81000.00),
(3, 2, 0, 66000.00),
(4, 2, 1, 68500.00),
(5, 3, 0, 43000.00),
(6, 3, 1, 45000.00),
(7, 3, 2, 47000.00),
(8, 4, 0, 29000.00),
(9, 4, 1, 30500.00),
(10, 5, 0, 22000.00),
(11, 5, 1, 23000.00),
(12, 6, 0, 16000.00),
(13, 6, 1, 16800.00),
(14, 7, 0, 12500.00),
(15, 8, 0, 10200.00),
(16, 9, 0, 9000.00);
-- Ensure the sequence for a serial/identity id is advanced after explicit inserts
SELECT setval(pg_get_serial_sequence('tbl_payscale_2015','id'),
              COALESCE((SELECT MAX(id) FROM tbl_payscale_2015), 1));

-- ------------------------------------------------------------
-- 14. tbl_policy
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_policy RESTART IDENTITY CASCADE;
INSERT INTO tbl_policy (id, policy_num, paycode, location, basic_from, basic_to, rate, fix_amount, min_amount, all_amt, eff_period) VALUES
(1, 'POL-HRA-01', '1002', NULL, 0.00, 30000.00, 0.4500, NULL, NULL, NULL, 202501),
(2, 'POL-HRA-02', '1002', NULL, 30001.00, 999999.00, 0.4000, NULL, NULL, NULL, 202501),
(3, 'POL-MED-01', '1003', NULL, 0.00, 999999.00, NULL, 1500.00, NULL, NULL, 202501),
(4, 'POL-CONV-01', '1004', NULL, 0.00, 999999.00, NULL, 1000.00, NULL, NULL, 202501),
(5, 'POL-PF-01', '2001', NULL, 0.00, 999999.00, 0.1000, NULL, NULL, NULL, 202501),
(6, 'POL-TAX-01', '2002', 'LOC01', 0.00, 999999.00, NULL, NULL, 500.00, NULL, 202501),
(7, 'POL-WEL-01', '2005', NULL, 0.00, 999999.00, NULL, 200.00, NULL, NULL, 202501),
(8, 'POL-DA-01', '1005', NULL, 0.00, 999999.00, 0.2000, NULL, NULL, NULL, 202501),
(9, 'POL-OT-01', '3001', NULL, 0.00, 999999.00, NULL, NULL, NULL, NULL, 202501);
SELECT setval(pg_get_serial_sequence('tbl_policy','id'),
              COALESCE((SELECT MAX(id) FROM tbl_policy), 1));

-- ------------------------------------------------------------
-- 15. tbl_employeemaster
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_employeemaster RESTART IDENTITY CASCADE;
INSERT INTO tbl_employeemaster (
  employeeid, name, fathername, sex, m_staus, religion,
  presentaddress, permanentaddress, district_id, lastdegree, bloodgroup,
  cpfgpfstatus, locationid, suspendstatus, ebsuspendstatus, incrementstatus,
  accommodation, employeetypeid, revenueprojectid, designationid, cadreid,
  chargestatus, accountnumber, joiningdatetopost, retireendcontractdate,
  dateofbirth, dateoffirstjoining, dateofregularization,
  overtimedailyrate, overtimehourlyrate, presentpaygrade, noofincrementspaid,
  nextincrementduedate, flag, bankbr_id, dir_divcell_id, nid, brid,
  mob_no, noofincrementspaid_old, nextincrementduedate_old, june_15_basic,
  group_id, pbsac_no, email, tin, child_1_dob, child_2_dob
) VALUES
('EMP001', 'Md. Abdul Karim', 'Md. Abdul Jalil', 'Male', 'Married', 'Islam',
 'House 12, Road 5, Dhanmondi, Dhaka', 'Vill: Mirpur, Upazila: Savar, Dhaka', 'DHK', 'M.Sc (Engg)', 'B+',
 'CPF', 'LOC01', '0', '0', 1,
 'Y', 'REG', 'REV05', 'DSG03', 'ENG',
 'Y', '1234567890123', '2010-03-15', '2035-12-31',
 '1975-06-20', '2005-01-10', '2008-06-01',
 2500.00, 350.00, 2, 3,
 '2026-07-01', 'A', 'SBL-DHK01', 'DIR02-DIV01', '1987654321098', 'BR001',
 '01711112233', 2, '2025-07-01', 66000.00,
 'G1', 'PBS-001', 'abdul.karim@reb.gov.bd', '1234567890', '2008-04-12', '2012-09-25'),

('EMP002', 'Fatema Begum', 'Md. Rafiqul Islam', 'Female', 'Married', 'Islam',
 'Flat 4B, Green Road, Dhaka', 'Vill: Comilla Sadar, Comilla', 'CML', 'MBA', 'A+',
 'GPF', 'LOC01', '0', '0', 1,
 'N', 'REG', 'REV05', 'DSG06', 'ACC',
 'Y', '9876543210987', '2012-07-01', '2040-06-30',
 '1982-11-05', '2009-03-20', '2012-07-01',
 1800.00, 250.00, 3, 2,
 '2026-07-01', 'A', 'ABL-DHK01', 'DIR03-DIV01', '1982123456789', 'BR002',
 '01812223344', 1, '2025-07-01', 43000.00,
 'G2', 'PBS-002', 'fatema.begum@reb.gov.bd', '0987654321', '2010-01-15', NULL),

('EMP003', 'Md. Rafiqul Hasan', 'Md. Habibur Rahman', 'Male', 'Married', 'Islam',
 'Agrabad C/A, Chittagong', 'Vill: Patiya, Chittagong', 'CTG', 'B.Sc Engg', 'O+',
 'CPF', 'LOC02', '0', '0', 1,
 'Y', 'REG', 'REV01', 'DSG05', 'ENG',
 'Y', '1122334455667', '2015-02-10', '2042-12-31',
 '1988-03-18', '2015-02-10', '2018-02-10',
 2000.00, 280.00, 3, 1,
 '2026-02-01', 'A', 'JBL-CTG01', 'DIR02-DIV02', '1988031812345', 'BR003',
 '01913334455', 0, NULL, 43000.00,
 'G1', 'PBS-003', 'rafiqul.hasan@reb.gov.bd', '1122334455', '2016-08-20', '2019-05-10'),

('EMP004', 'Nasrin Akter', 'Md. Shafiqul Alam', 'Female', 'Single', 'Islam',
 'Khulna Sadar, Khulna', 'Vill: Batiaghata, Khulna', 'KHL', 'B.Com', 'AB+',
 'GPF', 'LOC04', '0', '0', 1,
 'N', 'REG', 'REV05', 'DSG07', 'GEN',
 'Y', '5566778899001', '2018-05-15', '2045-05-14',
 '1993-09-22', '2018-05-15', '2021-05-15',
 1200.00, 180.00, 5, 0,
 '2026-05-01', 'A', 'RBL-RAJ01', 'DIR01-DIV01', '1993092212345', 'BR004',
 '01614445566', 0, NULL, 22000.00,
 'G3', 'PBS-004', 'nasrin.akter@reb.gov.bd', '5566778899', NULL, NULL),

('EMP005', 'Md. Kamrul Islam', 'Md. Anwar Hossain', 'Male', 'Married', 'Islam',
 'Rajshahi University Area, Rajshahi', 'Vill: Godagari, Rajshahi', 'RAJ', 'Diploma Engg', 'B-',
 'CPF', 'LOC03', '0', '0', 1,
 'Y', 'REG', 'REV02', 'DSG08', 'TEC',
 'Y', '6677889900112', '2016-11-01', '2043-10-31',
 '1990-12-10', '2016-11-01', '2019-11-01',
 1500.00, 200.00, 6, 1,
 '2026-11-01', 'A', 'RBL-RAJ01', 'DIR02-DIV01', '1990121012345', 'BR005',
 '01715556677', 0, NULL, 16000.00,
 'G3', 'PBS-005', 'kamrul.islam@reb.gov.bd', '6677889900', '2017-03-05', NULL);

-- ------------------------------------------------------------
-- 16. tbl_cpf_gpf_pct
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_cpf_gpf_pct RESTART IDENTITY CASCADE;
INSERT INTO tbl_cpf_gpf_pct (id, employeeid, cpf_gpf_pct, from_date) VALUES
(1, 'EMP001', 10.00, '2010-03-15'),
(2, 'EMP002', 8.00, '2012-07-01'),
(3, 'EMP003', 10.00, '2015-02-10'),
(4, 'EMP004', 8.00, '2018-05-15'),
(5, 'EMP005', 10.00, '2016-11-01');
SELECT setval(pg_get_serial_sequence('tbl_cpf_gpf_pct','id'),
              COALESCE((SELECT MAX(id) FROM tbl_cpf_gpf_pct), 1));

-- ------------------------------------------------------------
-- 17. tbl_user
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_user RESTART IDENTITY CASCADE;
INSERT INTO tbl_user (emp_id, user_name, user_pass, user_type, is_active) VALUES
('EMP000', 'admin', 'admin', 'ADM', 'Y'),
('EMP001', 'akarim', 'secret', 'USR', 'Y'),
('EMP002', 'fbegum', 'secret', 'USR', 'Y'),
('EMP003', 'rhasan', 'secret', 'USR', 'Y'),
('EMP004', 'nakter', 'secret', 'USR', 'Y'),
('EMP005', 'kislam', 'secret', 'USR', 'Y');

-- ------------------------------------------------------------
-- 18. tbl_advance_header
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_advance_header RESTART IDENTITY CASCADE;
INSERT INTO tbl_advance_header (
  loan_ref, employeeid, payid, account_no, loandate,
  p_amount, rate_interest, intinoneinstallment, p_amountpaid, i_amountpaid,
  i_accummulated, p_noofinstallments, p_noofinstallmentspaid,
  i_noofinstallments, i_noofinstallmentspaid,
  loan_closing_date, int_closing_date, starting_date, ending_date
) VALUES
('ADV-2024-001', 'EMP001', '2003', '1234567890123', '2024-01-15',
 100000.00, 5.00, 416.67, 40000.00, 2000.00,
 2500.00, 24, 8, 24, 8,
 NULL, NULL, '2024-02-01', '2026-01-31'),

('ADV-2024-002', 'EMP003', '2003', '1122334455667', '2024-06-01',
 50000.00, 5.00, 208.33, 10000.00, 500.00,
 800.00, 12, 2, 12, 2,
 NULL, NULL, '2024-07-01', '2025-06-30'),

('ADV-2023-015', 'EMP002', '2003', '9876543210987', '2023-03-10',
 30000.00, 4.00, 100.00, 30000.00, 1200.00,
 1200.00, 12, 12, 12, 12,
 '2024-03-01', '2024-03-01', '2023-04-01', '2024-03-31');

-- ------------------------------------------------------------
-- 19. tbl_advance_intaccumm
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_advance_intaccumm RESTART IDENTITY CASCADE;
INSERT INTO tbl_advance_intaccumm (id, loan_ref, period, amount) VALUES
(1, 'ADV-2024-001', 202402, 416.6700),
(2, 'ADV-2024-001', 202403, 416.6700),
(3, 'ADV-2024-001', 202404, 416.6700),
(4, 'ADV-2024-002', 202407, 208.3300),
(5, 'ADV-2024-002', 202408, 208.3300);
SELECT setval(pg_get_serial_sequence('tbl_advance_intaccumm','id'),
              COALESCE((SELECT MAX(id) FROM tbl_advance_intaccumm), 1));

-- ------------------------------------------------------------
-- 20. tbl_advance_tran
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_advance_tran RESTART IDENTITY CASCADE;
INSERT INTO tbl_advance_tran (id, loan_ref, trandate, amount, isprinciple, period, othermeans) VALUES
(1, 'ADV-2024-001', '2024-02-28', 4166.67, 'Y', 202402, 'N'),
(2, 'ADV-2024-001', '2024-02-28', 416.67, 'N', 202402, 'N'),
(3, 'ADV-2024-001', '2024-03-31', 4166.67, 'Y', 202403, 'N'),
(4, 'ADV-2024-001', '2024-03-31', 416.67, 'N', 202403, 'N'),
(5, 'ADV-2024-002', '2024-07-31', 4166.67, 'Y', 202407, 'N'),
(6, 'ADV-2024-002', '2024-07-31', 208.33, 'N', 202407, 'N'),
(7, 'ADV-2023-015', '2024-02-29', 2500.00, 'Y', 202402, 'N'),
(8, 'ADV-2023-015', '2024-02-29', 100.00, 'N', 202402, 'N');
SELECT setval(pg_get_serial_sequence('tbl_advance_tran','id'),
              COALESCE((SELECT MAX(id) FROM tbl_advance_tran), 1));

-- ------------------------------------------------------------
-- 21. tbl_paytran_master  (sample for period 202510)
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_paytran_master RESTART IDENTITY CASCADE;
INSERT INTO tbl_paytran_master (
  id, employeeid, payyear, paymonth, period,
  month_startbasic, name, locationid, suspendstatus, ebsuspendstatus,
  incrementstatus, accommodation, employeetypeid, revenueprojectid,
  designationid, cadreid, chargestatus, presentpaygrade,
  noofincrementspaid, nextincrementduedate, bankbr_id, dir_divcell_id,
  accountnumber, trcv, tdeduct
) VALUES
(1, 'EMP001', 2025, 10, 202510, 68500.00, 'Md. Abdul Karim', 'LOC01', '0', '0',
 1, 'Y', 'REG', 'REV05', 'DSG03', 'ENG', 'Y', 2, 3, '2026-07-01',
 'SBL-DHK01', 'DIR02-DIV01', '1234567890123', 112450.00, 18500.00),

(2, 'EMP002', 2025, 10, 202510, 45000.00, 'Fatema Begum', 'LOC01', '0', '0',
 1, 'N', 'REG', 'REV05', 'DSG06', 'ACC', 'Y', 3, 2, '2026-07-01',
 'ABL-DHK01', 'DIR03-DIV01', '9876543210987', 74200.00, 9800.00),

(3, 'EMP003', 2025, 10, 202510, 45000.00, 'Md. Rafiqul Hasan', 'LOC02', '0', '0',
 1, 'Y', 'REG', 'REV01', 'DSG05', 'ENG', 'Y', 3, 1, '2026-02-01',
 'JBL-CTG01', 'DIR02-DIV02', '1122334455667', 76500.00, 11200.00),

(4, 'EMP004', 2025, 10, 202510, 22000.00, 'Nasrin Akter', 'LOC04', '0', '0',
 1, 'N', 'REG', 'REV05', 'DSG07', 'GEN', 'Y', 5, 0, '2026-05-01',
 'RBL-RAJ01', 'DIR01-DIV01', '5566778899001', 36800.00, 4200.00),

(5, 'EMP005', 2025, 10, 202510, 16800.00, 'Md. Kamrul Islam', 'LOC03', '0', '0',
 1, 'Y', 'REG', 'REV02', 'DSG08', 'TEC', 'Y', 6, 1, '2026-11-01',
 'RBL-RAJ01', 'DIR02-DIV01', '6677889900112', 28500.00, 3500.00);
SELECT setval(pg_get_serial_sequence('tbl_paytran_master','id'),
              COALESCE((SELECT MAX(id) FROM tbl_paytran_master), 1));

-- ------------------------------------------------------------
-- 22. tbl_paytran_details
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_paytran_details RESTART IDENTITY CASCADE;
INSERT INTO tbl_paytran_details (
  id, payyear, paymonth, employeeid, payid, amount, ad,
  arrear, rule_no, remarks, jv_glcode, jv_rule_no,
  loan_ref_no, pay_type, src, period
) VALUES
-- EMP001 earnings
(1, 2025, 10, 'EMP001', '1001', 68500.00, 'A', 'N', 'RULE-BASIC', 'Basic Pay', NULL, NULL, NULL, 'REG', 'N', 202510),
(2, 2025, 10, 'EMP001', '1002', 27400.00, 'A', 'N', 'POL-HRA-02', 'HRA 40%', NULL, NULL, NULL, 'REG', 'N', 202510),
(3, 2025, 10, 'EMP001', '1003', 1500.00, 'A', 'N', 'POL-MED-01', 'Medical Allowance', NULL, NULL, NULL, 'REG', 'N', 202510),
(4, 2025, 10, 'EMP001', '1004', 1000.00, 'A', 'N', 'POL-CONV-01', 'Conveyance', NULL, NULL, NULL, 'REG', 'N', 202510),
(5, 2025, 10, 'EMP001', '1005', 13700.00, 'A', 'N', 'POL-DA-01', 'DA 20%', NULL, NULL, NULL, 'REG', 'N', 202510),
-- EMP001 deductions
(6, 2025, 10, 'EMP001', '2001', 6850.00, 'D', 'N', 'POL-PF-01', 'PF 10%', NULL, NULL, NULL, 'REG', 'N', 202510),
(7, 2025, 10, 'EMP001', '2002', 5500.00, 'D', 'N', 'POL-TAX-01', 'Income Tax', NULL, NULL, NULL, 'REG', 'N', 202510),
(8, 2025, 10, 'EMP001', '2003', 4166.67, 'D', 'N', NULL, 'Loan Principal', NULL, NULL, 'ADV-2024-001', 'REG', 'N', 202510),
(9, 2025, 10, 'EMP001', '2004', 416.67, 'D', 'N', NULL, 'Loan Interest', NULL, NULL, 'ADV-2024-001', 'REG', 'N', 202510),
(10, 2025, 10, 'EMP001', '2005', 200.00, 'D', 'N', 'POL-WEL-01', 'Welfare Fund', NULL, NULL, NULL, 'REG', 'N', 202510),

-- EMP002 earnings
(11, 2025, 10, 'EMP002', '1001', 45000.00, 'A', 'N', 'RULE-BASIC', 'Basic Pay', NULL, NULL, NULL, 'REG', 'N', 202510),
(12, 2025, 10, 'EMP002', '1002', 18000.00, 'A', 'N', 'POL-HRA-02', 'HRA 40%', NULL, NULL, NULL, 'REG', 'N', 202510),
(13, 2025, 10, 'EMP002', '1003', 1500.00, 'A', 'N', 'POL-MED-01', 'Medical Allowance', NULL, NULL, NULL, 'REG', 'N', 202510),
(14, 2025, 10, 'EMP002', '1004', 1000.00, 'A', 'N', 'POL-CONV-01', 'Conveyance', NULL, NULL, NULL, 'REG', 'N', 202510),
(15, 2025, 10, 'EMP002', '1005', 9000.00, 'A', 'N', 'POL-DA-01', 'DA 20%', NULL, NULL, NULL, 'REG', 'N', 202510),
-- EMP002 deductions
(16, 2025, 10, 'EMP002', '2001', 3600.00, 'D', 'N', 'POL-PF-01', 'PF 8%', NULL, NULL, NULL, 'REG', 'N', 202510),
(17, 2025, 10, 'EMP002', '2002', 2500.00, 'D', 'N', 'POL-TAX-01', 'Income Tax', NULL, NULL, NULL, 'REG', 'N', 202510),
(18, 2025, 10, 'EMP002', '2005', 200.00, 'D', 'N', 'POL-WEL-01', 'Welfare Fund', NULL, NULL, NULL, 'REG', 'N', 202510),

-- EMP003 earnings
(19, 2025, 10, 'EMP003', '1001', 45000.00, 'A', 'N', 'RULE-BASIC', 'Basic Pay', NULL, NULL, NULL, 'REG', 'N', 202510),
(20, 2025, 10, 'EMP003', '1002', 18000.00, 'A', 'N', 'POL-HRA-02', 'HRA 40%', NULL, NULL, NULL, 'REG', 'N', 202510),
(21, 2025, 10, 'EMP003', '1003', 1500.00, 'A', 'N', 'POL-MED-01', 'Medical Allowance', NULL, NULL, NULL, 'REG', 'N', 202510),
(22, 2025, 10, 'EMP003', '1004', 1000.00, 'A', 'N', 'POL-CONV-01', 'Conveyance', NULL, NULL, NULL, 'REG', 'N', 202510),
(23, 2025, 10, 'EMP003', '1005', 9000.00, 'A', 'N', 'POL-DA-01', 'DA 20%', NULL, NULL, NULL, 'REG', 'N', 202510),
(24, 2025, 10, 'EMP003', '3001', 2000.00, 'A', 'N', 'POL-OT-01', 'Overtime', NULL, NULL, NULL, 'REG', 'N', 202510),
-- EMP003 deductions
(25, 2025, 10, 'EMP003', '2001', 4500.00, 'D', 'N', 'POL-PF-01', 'PF 10%', NULL, NULL, NULL, 'REG', 'N', 202510),
(26, 2025, 10, 'EMP003', '2002', 2200.00, 'D', 'N', 'POL-TAX-01', 'Income Tax', NULL, NULL, NULL, 'REG', 'N', 202510),
(27, 2025, 10, 'EMP003', '2003', 4166.67, 'D', 'N', NULL, 'Loan Principal', NULL, NULL, 'ADV-2024-002', 'REG', 'N', 202510),
(28, 2025, 10, 'EMP003', '2004', 208.33, 'D', 'N', NULL, 'Loan Interest', NULL, NULL, 'ADV-2024-002', 'REG', 'N', 202510),
(29, 2025, 10, 'EMP003', '2005', 200.00, 'D', 'N', 'POL-WEL-01', 'Welfare Fund', NULL, NULL, NULL, 'REG', 'N', 202510),

-- EMP004 earnings
(30, 2025, 10, 'EMP004', '1001', 22000.00, 'A', 'N', 'RULE-BASIC', 'Basic Pay', NULL, NULL, NULL, 'REG', 'N', 202510),
(31, 2025, 10, 'EMP004', '1002', 9900.00, 'A', 'N', 'POL-HRA-01', 'HRA 45%', NULL, NULL, NULL, 'REG', 'N', 202510),
(32, 2025, 10, 'EMP004', '1003', 1500.00, 'A', 'N', 'POL-MED-01', 'Medical Allowance', NULL, NULL, NULL, 'REG', 'N', 202510),
(33, 2025, 10, 'EMP004', '1004', 1000.00, 'A', 'N', 'POL-CONV-01', 'Conveyance', NULL, NULL, NULL, 'REG', 'N', 202510),
(34, 2025, 10, 'EMP004', '1005', 4400.00, 'A', 'N', 'POL-DA-01', 'DA 20%', NULL, NULL, NULL, 'REG', 'N', 202510),
-- EMP004 deductions
(35, 2025, 10, 'EMP004', '2001', 1760.00, 'D', 'N', 'POL-PF-01', 'PF 8%', NULL, NULL, NULL, 'REG', 'N', 202510),
(36, 2025, 10, 'EMP004', '2002', 800.00, 'D', 'N', 'POL-TAX-01', 'Income Tax', NULL, NULL, NULL, 'REG', 'N', 202510),
(37, 2025, 10, 'EMP004', '2005', 200.00, 'D', 'N', 'POL-WEL-01', 'Welfare Fund', NULL, NULL, NULL, 'REG', 'N', 202510),

-- EMP005 earnings
(38, 2025, 10, 'EMP005', '1001', 16800.00, 'A', 'N', 'RULE-BASIC', 'Basic Pay', NULL, NULL, NULL, 'REG', 'N', 202510),
(39, 2025, 10, 'EMP005', '1002', 7560.00, 'A', 'N', 'POL-HRA-01', 'HRA 45%', NULL, NULL, NULL, 'REG', 'N', 202510),
(40, 2025, 10, 'EMP005', '1003', 1500.00, 'A', 'N', 'POL-MED-01', 'Medical Allowance', NULL, NULL, NULL, 'REG', 'N', 202510),
(41, 2025, 10, 'EMP005', '1004', 1000.00, 'A', 'N', 'POL-CONV-01', 'Conveyance', NULL, NULL, NULL, 'REG', 'N', 202510),
(42, 2025, 10, 'EMP005', '1005', 3360.00, 'A', 'N', 'POL-DA-01', 'DA 20%', NULL, NULL, NULL, 'REG', 'N', 202510),
-- EMP005 deductions
(43, 2025, 10, 'EMP005', '2001', 1680.00, 'D', 'N', 'POL-PF-01', 'PF 10%', NULL, NULL, NULL, 'REG', 'N', 202510),
(44, 2025, 10, 'EMP005', '2002', 600.00, 'D', 'N', 'POL-TAX-01', 'Income Tax', NULL, NULL, NULL, 'REG', 'N', 202510),
(45, 2025, 10, 'EMP005', '2005', 200.00, 'D', 'N', 'POL-WEL-01', 'Welfare Fund', NULL, NULL, NULL, 'REG', 'N', 202510);
SELECT setval(pg_get_serial_sequence('tbl_paytran_details','id'),
              COALESCE((SELECT MAX(id) FROM tbl_paytran_details), 1));

-- ------------------------------------------------------------
-- 23. tbl_emp_last_salary
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_emp_last_salary RESTART IDENTITY CASCADE;
INSERT INTO tbl_emp_last_salary (
  id, payyear, paymonth, employeeid, payid, amount, ad, period, arrear, jv_glcode, remarks
) VALUES
(1, 2025, 9, 'EMP001', '1001', 68500.00, 'A', 202509, 'N', NULL, 'Previous month basic'),
(2, 2025, 9, 'EMP001', '1002', 27400.00, 'A', 202509, 'N', NULL, 'Previous month HRA'),
(3, 2025, 9, 'EMP002', '1001', 45000.00, 'A', 202509, 'N', NULL, 'Previous month basic'),
(4, 2025, 9, 'EMP003', '1001', 45000.00, 'A', 202509, 'N', NULL, 'Previous month basic'),
(5, 2025, 9, 'EMP004', '1001', 22000.00, 'A', 202509, 'N', NULL, 'Previous month basic'),
(6, 2025, 9, 'EMP005', '1001', 16800.00, 'A', 202509, 'N', NULL, 'Previous month basic');
SELECT setval(pg_get_serial_sequence('tbl_emp_last_salary','id'),
              COALESCE((SELECT MAX(id) FROM tbl_emp_last_salary), 1));

-- ------------------------------------------------------------
-- 24. tbl_increment
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_increment RESTART IDENTITY CASCADE;
INSERT INTO tbl_increment (
  id, period, employee_id, incr_date, incr_by,
  prev_grade, prev_incr, prev_incr_date,
  new_grade, new_incr, new_incr_date
) VALUES
(1, 202507, 'EMP001', '2025-07-01 10:00:00', 'ADMIN', 2, 2, '2024-07-01', 2, 3, '2025-07-01'),
(2, 202507, 'EMP002', '2025-07-01 10:05:00', 'ADMIN', 3, 1, '2024-07-01', 3, 2, '2025-07-01'),
(3, 202502, 'EMP003', '2025-02-01 09:30:00', 'ADMIN', 3, 0, '2018-02-10', 3, 1, '2025-02-01');
SELECT setval(pg_get_serial_sequence('tbl_increment','id'),
              COALESCE((SELECT MAX(id) FROM tbl_increment), 1));

-- ------------------------------------------------------------
-- 25. tbl_promotions
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_promotions RESTART IDENTITY CASCADE;
INSERT INTO tbl_promotions (
  id, employee_id, eff_period, promote_date,
  old_grade, old_incrm, new_grade, new_incrm
) VALUES
(1, 'EMP001', 201803, '2018-03-15', 3, 2, 2, 0),
(2, 'EMP002', 201907, '2019-07-01', 4, 1, 3, 0);
SELECT setval(pg_get_serial_sequence('tbl_promotions','id'),
              COALESCE((SELECT MAX(id) FROM tbl_promotions), 1));

-- ------------------------------------------------------------
-- 26. tbl_specialpay_tran
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_specialpay_tran RESTART IDENTITY CASCADE;
INSERT INTO tbl_specialpay_tran (
  id, employeeid, period, payid, ad, amount, withpayslip, particulars
) VALUES
(1, 'EMP001', 202510, '3002', 'A', 5000.00, 'Y', 'Arrear adjustment for July-Sep 2025'),
(2, 'EMP003', 202510, '3001', 'A', 3500.00, 'Y', 'Extra overtime for project work');
SELECT setval(pg_get_serial_sequence('tbl_specialpay_tran','id'),
              COALESCE((SELECT MAX(id) FROM tbl_specialpay_tran), 1));

-- ------------------------------------------------------------
-- 27. tbl_not_paid_emp_by_ymd
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_not_paid_emp_by_ymd RESTART IDENTITY CASCADE;
INSERT INTO tbl_not_paid_emp_by_ymd (id, employeeid, period) VALUES
(1, 'EMP004', 202508);
SELECT setval(pg_get_serial_sequence('tbl_not_paid_emp_by_ymd','id'),
              COALESCE((SELECT MAX(id) FROM tbl_not_paid_emp_by_ymd), 1));

-- ------------------------------------------------------------
-- 28. tbl_utxmaster  (overtime / leave / LWP tracking)
-- ------------------------------------------------------------
TRUNCATE TABLE tbl_utxmaster RESTART IDENTITY CASCADE;
INSERT INTO tbl_utxmaster (
  id, employeeid, period, payyear, paymonth,
  otdays, otdaysrate, othrs, othrsrate, eldays, lwp
) VALUES
(1, 'EMP003', 202510, 2025, 10, 2.00, 2000.00, 8.00, 280.00, 0.00, 0.00);
SELECT setval(pg_get_serial_sequence('tbl_utxmaster','id'),
              COALESCE((SELECT MAX(id) FROM tbl_utxmaster), 1));

COMMIT;