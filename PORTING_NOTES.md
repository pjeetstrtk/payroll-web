# Porting notes - what is and is not in this Laravel 12 / MySQL 8.0.33 port

## Why this is a best-effort port
The uploaded zip was a *published* ASP.NET WebForms site. The page markup (`.aspx`) was available, but the
C# code-behind was only inside the compiled `bin/RebPayRollWeb.dll`, and the Oracle schema (tables, views,
procedures) and the ~50 Crystal Report layouts were not included. So:

* Screens, field names and SQL were rebuilt from the `.aspx` markup and the SQL strings found in the DLL.
* The database schema was **inferred** (see the first migration). Compare it with your real Oracle DDL.
* Calculation logic was **reconstructed** from the order of operations in the compiled code. Anything
  marked `VERIFY` in `app/Services/SalaryProcessor.php` and `SalaryController::doClose()` is an educated guess.
* Nothing here has been executed: Composer packages could not be installed in the environment where this
  was produced, so only PHP syntax was checked (`php -l`). Expect to fix small things on first run.

## Ported
| Original page(s) | Laravel |
|---|---|
| Login, Logout, Change Password | `AuthController` (bcrypt; same password rules as the original) |
| Dashboard (my salary) | `DashboardController` |
| Bank, Bank Branch, Cadre, Designation, Directorate, Div/Cell, Location, Revenue Project, Pay Codes, Pay Scale, House Rent, Tax Rate, CPF/GPF %, Promotion, Overtime, Special allowances/deductions/bonus, Loan headers, Not-paid list | One generic CRUD (`LookupController`, defined in `config/lookups.php`) |
| Employee add / edit / search / view / joined / end-contract | `EmployeeController` |
| Process Salary, Month Close | `SalaryProcessor`, `SalaryController` |
| Payslip, Salary Summary, Bank Advice, Journal Voucher | `ReportController` (HTML/print; not the Crystal layouts) |

## NOT ported (use `docs/recovered_logic/<PageName>.txt` as the specification)
Festival bonus entry and bonus slip, KPI bonus, donation, salary hold / receive-unreceive, employee salary
settings, salary certificate, per-directorate salary process variants (`frmSalaryProcessDIRUser`,
`frmSalaryProcess_dir`, `frmSalaryProcessAll`), e-mail payslip, UTX print, loan & advance entry with interest
accumulation, loan ledger / details, HB loan certificate, income-tax settings / monthly tax process /
schedule / certificate (`TAX.txt`, `frmTaxProcessAll.txt`), ledger accounts (JV account mapping), directorate
users, Form 129 / e-slip, MyProfile, the ~50 Crystal reports (CPR, CPF/GPF statements, HB statements,
loan statements, tax statements, certificates, ...).

## Known simplifications / things to verify
* `SalaryProcessor`: promotion day-weighting, house-rent formula (rate % with minimum), CPF/GPF % of basic,
  loan principal instalment (principal / instalments) and interest (`intinoneinstallment`) are reconstructed.
  The leave-without-pay / earned-leave deduction formula could not be recovered and is **not applied**
  (a warning is shown). The original also had policy-driven allowances (`GetPolicySettingAmount`) and
  upper-position charge (`GetUpperPositionCharge`, pay id 39) that are not implemented.
* The per-employee allowance template (`tbl_emp_last_salary`) must be loaded from your data for the first month;
  Month Close then rolls it forward from the processed month.
* Month Close applies the annual increment when closing June - verify against your policy.
* `SUSPENDSTATUS`, `ACCOMMODATION`, `CHARGESTATUS` and other flags are stored but have no effect yet.

## Security changes
* The original `Web.config` contained an Oracle user/password and built SQL by string concatenation
  (SQL-injection prone) and stored passwords in clear text. None of that was carried over: this port uses
  bound parameters, bcrypt, CSRF protection and a role middleware. **Rotate the old Oracle password.**
* Change the seeded `admin` password immediately.
