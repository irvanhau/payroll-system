<?php

use App\Http\Controllers\Report\BalanceSheetController;
use App\Http\Controllers\Report\GeneralLedgerController;
use App\Http\Controllers\Report\ProfitLossController;
use App\Http\Controllers\Report\SalaryPeriodReportController;
use App\Http\Controllers\Report\TrialBalanceController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/report/balance-sheet', [BalanceSheetController::class, 'index'])->name('balance-sheet.index');
    Route::get('/report/profit-loss', [ProfitLossController::class, 'index'])->name('profit-loss.index');
    Route::get('/report/general-ledger', [GeneralLedgerController::class, 'index'])->name('general-ledger.index');
    Route::get('/report/trial-balance', [TrialBalanceController::class, 'index'])->name('trial-balance.index');
    Route::get('/report/salary-period-report', [SalaryPeriodReportController::class, 'index'])->name('salary-period-report.index');

    // DOWNLOAD PDF
    Route::get('/report/balance-sheet/download/pdf', [BalanceSheetController::class, 'generatePdf'])->name('balance-sheet.pdf');
    Route::get('/report/profit-loss/download/pdf', [ProfitLossController::class, 'generatePdf'])->name('profit-loss.pdf');
    Route::get('/report/general-ledger/download/pdf', [GeneralLedgerController::class, 'generatePdf'])->name('general-ledger.pdf');
    Route::get('/report/trial-balance/download/pdf', [TrialBalanceController::class, 'generatePdf'])->name('trial-balance.pdf');

    // DOWNLOAD EXCEL
    Route::get('/report/balance-sheet/download/excel', [BalanceSheetController::class, 'generateExcel'])->name('balance-sheet.excel');
    Route::get('/report/profit-loss/download/excel', [ProfitLossController::class, 'generateExcel'])->name('profit-loss.excel');
    Route::get('/report/general-ledger/download/excel', [GeneralLedgerController::class, 'generateExcel'])->name('general-ledger.excel');
    Route::get('/report/trial-balance/download/excel', [TrialBalanceController::class, 'generateExcel'])->name('trial-balance.excel');
});
