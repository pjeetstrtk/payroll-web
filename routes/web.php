<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LookupController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalaryController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/password', [AuthController::class, 'showPassword'])->name('password');
    Route::post('/password', [AuthController::class, 'updatePassword'])->name('password.update');
    Route::get('/reports/payslip', [ReportController::class, 'payslip'])->name('reports.payslip');

    // Administrators / managers (TBL_USER.USER_TYPE ADM or MNG)
    Route::middleware('role')->group(function () {
        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('/employees/list/{kind}', [EmployeeController::class, 'monthList'])->name('employees.month');
        Route::get('/employees/{id}', [EmployeeController::class, 'show'])->name('employees.show');
        Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');

        Route::prefix('lookup/{slug}')->controller(LookupController::class)->group(function () {
            Route::get('/', 'index')->name('lookup.index');
            Route::post('/', 'store')->name('lookup.store');
            Route::put('/{key}', 'update')->name('lookup.update')->where('key', '[^/]+');
            Route::delete('/{key}', 'destroy')->name('lookup.destroy')->where('key', '[^/]+');
        });

        Route::get('/salary/process', [SalaryController::class, 'process'])->name('salary.process');
        Route::post('/salary/process', [SalaryController::class, 'run'])->name('salary.run');
        Route::get('/salary/close', [SalaryController::class, 'close'])->name('salary.close');
        Route::post('/salary/close', [SalaryController::class, 'doClose'])->name('salary.doclose');

        Route::get('/reports/summary', [ReportController::class, 'summary'])->name('reports.summary');
        Route::get('/reports/bank-advice', [ReportController::class, 'bankAdvice'])->name('reports.bank');
        Route::get('/reports/jv', [ReportController::class, 'jv'])->name('reports.jv');
    });
});
