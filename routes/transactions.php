<?php

use App\Http\Controllers\EmployeeAllowanceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDeductionController;
use App\Http\Controllers\EmployeeSalaryPeriodController;
use App\Http\Controllers\Report\SalaryPeriodReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // ALLOWANCE
    Route::get('/employee/{id}/allowance', [EmployeeAllowanceController::class, 'index'])->name('employee-allowance.index');
    Route::post('/employee/{id}/allowance', [EmployeeAllowanceController::class, 'store'])->name('employee-allowance.store');
    Route::delete('/employee/{employee_id}/allowance/{allowance_id}', [EmployeeAllowanceController::class, 'destroy'])->name('employee-allowance.destroy');

    // DEDUCTION
    Route::get('/employee/{id}/deduction', [EmployeeDeductionController::class, 'index'])->name('employee-deduction.index');
    Route::post('/employee/{id}/deduction', [EmployeeDeductionController::class, 'store'])->name('employee-deduction.store');
    Route::delete('/employee/{employee_id}/deduction/{deduction_id}', [EmployeeDeductionController::class, 'destroy'])->name('employee-deduction.destroy');

    // EMPLOYEE
    Route::get('/employee/{id}/generate-net-salary', [EmployeeController::class, 'generateNetSalary'])->name('employee.generateNetSalary');

    // EMPLOYEE SALARY PERIOD
    Route::resource('employee-salary-period', EmployeeSalaryPeriodController::class);
    Route::get('jurnal/{record_id}/employee', [SalaryPeriodReportController::class, 'viewJurnal']);

    // TEST EMAIL
    // Route::get('/test-email', [EmployeeSalaryPeriodController::class, 'sendEmail']);
});
