# Payroll Management System - Laravel 12 / MySQL 8.0.33

## Requirements

PHP 8.2+ (8.3 recommended), Composer 2, MySQL 8.0.33, extensions: pdo_mysql, mbstring, openssl, xml, ctype, json.

## Install

```bash
composer install
cp .env.example .env
php artisan key:generate
# create an empty database first:  CREATE DATABASE rebpayroll CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
# then set DB_* in .env
php artisan migrate --seed
php artisan serve
```

Open http://localhost:8000 and log in with `admin` / `Admin@12345`, then change the password
(menu: Change Password). `UserType` ADM and MNG see every menu; any other type only sees their own payslip.

## Loading your existing Oracle data

Export each table from Oracle (CSV) and import into the matching lower-case MySQL table
(`TBL_EMPLOYEEMASTER` -> `tbl_employeemaster`, ...). Convert dates to `YYYY-MM-DD`.
The original stored user passwords in clear text; after importing `tbl_user` run:

```bash
php artisan payroll:hash-passwords
```

The MySQL views (`vw_employee_all`, `vw_salary_details`, `vw_salary_sum`, ...) are created by the migrations.
Before you load real data, compare `database/migrations/2026_10_17_000001_create_payroll_tables.php` with your
real Oracle DDL - the schema here was inferred (the original DDL was not in the zip).
