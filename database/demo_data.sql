-- Demo data for the BREB payroll Laravel 12 port (MySQL 8.0.33)
-- Run AFTER:  php artisan migrate --seed
-- Usage:      mysql -u root -p rebpayroll < database/demo_data.sql
-- Safe to re-run (uses REPLACE / ON DUPLICATE KEY). All people and accounts below are fictitious.
-- Demo logins: admin (from seeder) / Admin@12345 ; manager1 and EMP1002 / Demo@12345

SET NAMES utf8mb4;

-- current payroll period
UPDATE tbl_company_info SET company_name = 'BREB (Demo)', curr_period = 202610, close_period = 202609;

-- users (ADM / MNG see every menu, USR sees own payslip only)
REPLACE INTO tbl_user (emp_id, user_name, user_pass, user_type, is_active) VALUES
('manager1', 'Demo Manager',  '$2y$10$m.nJs.oZ29wKpbia3UrZOOz2bv9vGwHvz.vNo5YApiHZWg/BQbKyi', 'MNG', 'Y'),
('EMP1002',  'Rahim Uddin',   '$2y$10$m.nJs.oZ29wKpbia3UrZOOz2bv9vGwHvz.vNo5YApiHZWg/BQbKyi', 'USR', 'Y');

-- banks & branches
REPLACE INTO tbl_bank (bankid, bank_description, gl_code) VALUES
('01', 'Sonali Bank Limited', '1411-01-001-000-00-0'),
('02', 'Janata Bank Limited', '1411-01-002-000-00-0');
REPLACE INTO tbl_bank_branch (br_id, bankid, branchid, branch_description) VALUES
('01-001', '01', '001', 'Principal Branch, Dhaka'),
('01-002', '01', '002', 'Agargaon Branch, Dhaka'),
('02-001', '02', '001', 'Bogura Branch');

-- organisation masters
REPLACE INTO tbl_cadre (cadreid, cadre_description) VALUES ('1', 'Engineering'), ('2', 'Finance & Accounts'), ('3', 'General / Admin');
REPLACE INTO tbl_management (managementid, mgmt_description) VALUES ('1', 'Officer'), ('2', 'Staff');
REPLACE INTO tbl_designation (designationid, dsg_description, class, hourly_dist, managementid) VALUES
('101', 'Chief Engineer',      '1', NULL, '1'),
('102', 'Assistant Engineer',  '1', NULL, '1'),
('201', 'Accountant',          '2', NULL, '1'),
('301', 'Office Assistant',    '3', NULL, '2'),
('302', 'Driver',              '4', 'Y',  '2');
REPLACE INTO tbl_directorate (directorateid, dir_description) VALUES
('100', 'Engineering Directorate'), ('200', 'Finance Directorate'), ('300', 'Administration Directorate');
REPLACE INTO tbl_divisionsectioncell (dir_divcell_id, directorateid, divisioncellid, divcel_description) VALUES
('100-0', '100', '0', 'Chief Engineer Office'),
('100-1', '100', '1', 'Planning Division'),
('200-0', '200', '0', 'Accounts Division'),
('300-0', '300', '0', 'General Administration');
REPLACE INTO tbl_location (locationid, location_description, min_tax) VALUES
('01', 'Dhaka', 5000.00), ('02', 'Bogura', 3000.00), ('03', 'Other Districts', 3000.00);
REPLACE INTO tbl_project_revenue (revenueprojectid, revenue_proj_description) VALUES ('01', 'Revenue Budget'), ('02', 'Development Project A');

-- pay codes (01, 02, 10, 29, 30 already exist from the seeder and are used by the salary engine)
REPLACE INTO tbl_paycode (payid, payname, ad, cpr_order, gl_code, pay_name_gl, adv_int_code, gl_13_20) VALUES
('01', 'Basic Pay',               'A', 1,  '4111-01-000-000-00-0', 'Basic Pay',          NULL, NULL),
('02', 'House Rent Allowance',    'A', 2,  '4111-02-000-000-00-0', 'House Rent',         NULL, NULL),
('03', 'Medical Allowance',       'A', 3,  '4111-03-000-000-00-0', 'Medical Allowance',  NULL, NULL),
('04', 'Conveyance Allowance',    'A', 4,  '4111-04-000-000-00-0', 'Conveyance',         NULL, NULL),
('10', 'GPF/CPF Contribution',    'D', 10, '2111-01-000-000-00-0', 'GPF/CPF',            NULL, NULL),
('29', 'Overtime (Days)',         'A', 5,  '4111-09-000-000-00-0', 'Overtime',           NULL, NULL),
('30', 'Overtime (Hours)',        'A', 6,  '4111-09-000-000-00-0', 'Overtime',           NULL, NULL),
('40', 'Welfare Fund',            'D', 11, '2111-05-000-000-00-0', 'Welfare Fund',       NULL, NULL),
('50', 'House Building Loan',     'D', 20, '1311-01-000-000-00-0', 'HB Loan Principal',  '51', NULL),
('51', 'House Building Loan Int.','D', 21, '1311-02-000-000-00-0', 'HB Loan Interest',   NULL, NULL);

-- pay scale (grade / increment -> basic). Demo values, not the real national pay scale.
DELETE FROM tbl_payscale_2015;
INSERT INTO tbl_payscale_2015 (pay_grade, increment_number, basic) VALUES
(3, 0, 56500.00), (3, 1, 59300.00), (3, 2, 62200.00), (3, 3, 65300.00),
(6, 0, 35500.00), (6, 1, 37300.00), (6, 2, 39100.00), (6, 3, 41000.00),
(9, 0, 22000.00), (9, 1, 23100.00), (9, 2, 24200.00), (9, 3, 25400.00),
(14, 0, 11000.00), (14, 1, 11550.00), (14, 2, 12100.00), (14, 3, 12700.00);

-- policies: house rent (paycode 02) and income-tax slabs (paycode 101)
DELETE FROM tbl_policy;
INSERT INTO tbl_policy (policy_num, paycode, location, basic_from, basic_to, rate, fix_amount, min_amount, eff_period) VALUES
('HR-1', '02', '01', 0,     29999.99, 60.00, NULL, 5000.00, 201507),
('HR-1', '02', '01', 30000, 999999,   50.00, NULL, 18000.00, 201507),
('HR-1', '02', '02', 0,     29999.99, 50.00, NULL, 4000.00, 201507),
('HR-1', '02', '02', 30000, 999999,   45.00, NULL, 13500.00, 201507),
('HR-1', '02', '03', 0,     999999,   45.00, NULL, 4000.00, 201507),
('TX-1', '101', NULL, 0,      350000,  0.00,  0,      NULL, 202507),
('TX-1', '101', NULL, 350001, 450000,  5.00,  0,      NULL, 202507),
('TX-1', '101', NULL, 450001, 750000, 10.00,  5000,   NULL, 202507),
('TX-1', '101', NULL, 750001, 1150000,15.00, 35000,   NULL, 202507);

-- employees
REPLACE INTO tbl_employeemaster
(employeeid, name, fathername, sex, m_staus, religion, presentaddress, permanentaddress, designationid, cadreid,
 locationid, revenueprojectid, employeetypeid, dir_divcell_id, bankbr_id, accountnumber, dateofbirth, dateoffirstjoining,
 joiningdatetopost, dateofregularization, retireendcontractdate, presentpaygrade, noofincrementspaid, nextincrementduedate,
 incrementstatus, suspendstatus, ebsuspendstatus, cpfgpfstatus, accommodation, mob_no, email, nid, tin, overtimedailyrate, overtimehourlyrate) VALUES
('EMP1001','Karim Ahmed','Abdul Ahmed','Male','Married','Islam','Dhaka','Bogura','101','1','01','01','R','100-0','01-001','1001000100011','1968-03-12','1995-07-01','2018-01-01','1996-07-01','2028-03-11',3,3,'2027-07-01',1,'0','0','1','N','01700000001','karim@example.com','1968123456789','100000000001',NULL,NULL),
('EMP1002','Rahim Uddin','Jalal Uddin','Male','Married','Islam','Dhaka','Rangpur','102','1','01','01','R','100-1','01-001','1001000100022','1985-09-20','2012-02-01','2012-02-01','2013-02-01','2045-09-19',6,2,'2027-07-01',1,'0','0','1','N','01700000002','rahim@example.com','1985123456780','100000000002',NULL,NULL),
('EMP1003','Nasrin Sultana','Abul Hossain','Female','Married','Islam','Dhaka','Khulna','201','2','01','01','R','200-0','01-002','1001000100033','1983-11-05','2010-06-15','2010-06-15','2011-06-15','2043-11-04',6,3,'2027-07-01',1,'0','0','1','N','01700000003','nasrin@example.com','1983123456781','100000000003',NULL,NULL),
('EMP1004','Shamim Reza','Md. Reza','Male','Single','Islam','Bogura','Bogura','301','3','02','01','R','300-0','02-001','2001000100044','1992-01-30','2016-04-01','2016-04-01','2017-04-01','2052-01-29',9,2,'2027-07-01',1,'0','0','2','N','01700000004','shamim@example.com','1992123456782','100000000004',NULL,NULL),
('EMP1005','Anwar Hossain','Habib Hossain','Male','Married','Islam','Dhaka','Comilla','302','3','01','01','R','300-0','01-001','1001000100055','1980-07-07','2005-08-01','2005-08-01','2006-08-01','2040-07-06',14,3,'2027-07-01',1,'0','0','1','N','01700000005','anwar@example.com','1980123456783','100000000005',450.00,56.25),
('EMP1006','Sunita Rani Das','Gopal Das','Female','Married','Hindu','Dhaka','Barisal','102','1','01','02','R','100-1','01-002','1001000100066','1988-04-18','2014-09-01','2014-09-01','2015-09-01','2048-04-17',6,1,'2027-07-01',1,'0','0','2','N','01700000006','sunita@example.com','1988123456784','100000000006',NULL,NULL),
('EMP1007','Mizanur Rahman','Late Abdur Rahman','Male','Married','Islam','Bogura','Bogura','201','2','02','01','R','200-0','02-001','2001000100077','1975-12-25','2000-01-10','2000-01-10','2001-01-10','2035-12-24',9,3,'2027-07-01',1,'0','0','1','N','01700000007','mizan@example.com','1975123456785','100000000007',NULL,NULL),
('EMP1008','Farhana Yasmin','Shafiqul Islam','Female','Single','Islam','Dhaka','Sylhet','301','3','01','01','C','300-0','01-001','1001000100088','1996-06-02','2022-03-01','2022-03-01',NULL,'2026-12-31',14,0,'2027-03-01',0,'0','0','2','N','01700000008','farhana@example.com','1996123456786','100000000008',NULL,NULL);

-- CPF/GPF contribution percentages
DELETE FROM tbl_cpf_gpf_pct;
INSERT INTO tbl_cpf_gpf_pct (employeeid, cpf_gpf_pct, from_date) VALUES
('EMP1001', 10.00, '2018-01-01'), ('EMP1002', 10.00, '2012-02-01'), ('EMP1003', 10.00, '2010-06-15'),
('EMP1005', 10.00, '2005-08-01'), ('EMP1007', 10.00, '2000-01-10');

-- recurring allowance/deduction template carried month to month (what Month Close rolls forward)
DELETE FROM tbl_emp_last_salary;
INSERT INTO tbl_emp_last_salary (payyear, paymonth, employeeid, payid, amount, ad, period, arrear, remarks)
SELECT 2026, 10, e.employeeid, t.payid, t.amount, t.ad, 202610, 'N', NULL
FROM tbl_employeemaster e
JOIN (SELECT '03' payid, 1500.00 amount, 'A' ad UNION ALL SELECT '04', 2500.00, 'A' UNION ALL SELECT '40', 100.00, 'D') t;

-- overtime for the driver in the current period
DELETE FROM tbl_utxmaster WHERE period = 202610;
INSERT INTO tbl_utxmaster (employeeid, period, payyear, paymonth, otdays, otdaysrate, othrs, othrsrate, eldays, lwp) VALUES
('EMP1005', 202610, 2026, 10, 2, 450.00, 6, 56.25, 0, 0);

-- one-off special pay this period (festival bonus for two employees)
DELETE FROM tbl_specialpay_tran WHERE period = 202610;
INSERT INTO tbl_specialpay_tran (employeeid, period, payid, ad, amount, withpayslip, particulars) VALUES
('EMP1002', 202610, '04', 'A', 5000.00, 'Y', 'Festival allowance (demo)'),
('EMP1003', 202610, '04', 'A', 5000.00, 'Y', 'Festival allowance (demo)');

-- a house-building loan: 24 instalments of principal, interest per instalment
REPLACE INTO tbl_advance_header
(loan_ref, employeeid, payid, account_no, loandate, p_amount, rate_interest, intinoneinstallment, p_amountpaid, i_amountpaid,
 i_accummulated, p_noofinstallments, p_noofinstallmentspaid, i_noofinstallments, i_noofinstallmentspaid, starting_date, ending_date) VALUES
('HB-2026-0001', 'EMP1003', '50', 'HB-0001', '2026-07-01', 240000.00, 5.00, 500.00, 0, 0, 0, 24, 0, 24, 0, '2026-08-01', '2028-07-31');

-- one employee excluded from this month's payroll (shows the "not paid" list)
REPLACE INTO tbl_not_paid_emp_by_ymd (employeeid, period) VALUES ('EMP1008', 202610);
