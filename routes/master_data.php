<?php

use App\Http\Controllers\AllowanceController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\DeductionController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::resource('employee', EmployeeController::class)->except('destroy');
    Route::resource('chart-of-account', ChartOfAccountController::class)->except('destroy');
    Route::resource('allowance', AllowanceController::class)->except('destroy');
    Route::resource('deduction', DeductionController::class)->except('destroy');

    Route::get('/chart-of-account/set_status/{id}', [ChartOfAccountController::class, 'setStatus'])->name('chart-of-account.setStatus');
    Route::get('/employee/set_status/{id}', [EmployeeController::class, 'setStatus'])->name('employee.setStatus');
});
