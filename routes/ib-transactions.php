<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\IBTransactionController;

/*
|--------------------------------------------------------------------------
| IB Transactions Routes
|--------------------------------------------------------------------------
|
| Routes for managing IB transactions in quarter-based tables
|
*/

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    
    // IB Transactions Management
    Route::get('/ib-transactions', [IBTransactionController::class, 'index'])->name('admin.ib-transactions.index');
    Route::get('/ib-transactions/summary', [IBTransactionController::class, 'summary'])->name('admin.ib-transactions.summary');
    Route::get('/ib-transactions/export', [IBTransactionController::class, 'export'])->name('admin.ib-transactions.export');
    Route::get('/ib-transactions/{tnx}', [IBTransactionController::class, 'show'])->name('admin.ib-transactions.show');
    
});
