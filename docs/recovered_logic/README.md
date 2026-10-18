# Recovered logic from RebPayRollWeb.dll

The original zip was a *published* ASP.NET site: the C# code-behind existed only inside the compiled
`bin/RebPayRollWeb.dll`. One text file per page/business class is kept here. Each lists, in execution order,
the SQL strings, field names and notable method calls found in that class's compiled code
(`SalaryProcess.txt`, `TAX.txt`, `Loans.txt`, `JournalVoucher.txt`, `frmSalaryProcess.txt`, ...).

They are NOT source code - arithmetic operators and local variable names are not shown - but they list the
tables, columns and SQL the original used. Use them as the specification when porting the remaining pages
(see `../../PORTING_NOTES.md`). Oracle syntax (SYSDATE, to_char, nvl, ||, ROWNUM) must be converted to MySQL.
