<?php

use App\Http\Controllers\Frontend\PricingInvestmentController;
use App\Http\Controllers\Frontend\PricingInvestController;
use Illuminate\Support\Facades\Route;


Route::name('admin.pricing.')->middleware(['admin'])->prefix('admin/pricing/investment')->group(function () {
    Route::get('/dashboard', 'Admin\InvestDashboardController@index')->name('dashboard');;

    Route::get('/history/{status?}', 'Admin\PricingInvestedPlansController@investedPlanList')->name('list');
    Route::get('/plan/details/{id}', 'Admin\PricingInvestedPlansController@showInvestmentDetails')->name('details');
    Route::get('/plan/action', 'Admin\PricingInvestedPlansController@showInvestmentDetails')->name('plan.action');
    Route::post('plan/approve/{id?}', 'Admin\PricingInvestedPlansController@approveInvestment')->name('plan.approve');
    Route::post('plan/cancel/{id?}', 'Admin\PricingInvestedPlansController@cancelInvestment')->name('plan.cancel');
    Route::post('plan/migrate/{id?}', 'Admin\PricingInvestedPlansController@migrateInvestment')->name('plan.migrate');

    Route::get('/profits/{type?}', 'Admin\LedgerProfitsController@profitList')->name('profits.list');
    Route::get('/transactions/{type?}', 'Admin\LedgerProfitsController@transactionList')->name('transactions.list');

    Route::post('/payout/profits', 'Admin\LedgerProfitsController@profitsPayout')->name('profits.payout');

    // Schemes
    Route::get('/schemes/{status?}', 'Admin\PricingInvestmentSchemeController@schemeList')->name('schemes');
    Route::get('/scheme/{action?}', 'Admin\PricingInvestmentSchemeController@actionScheme')->name('scheme.action');
    Route::post('/scheme/update/{id?}', 'Admin\PricingInvestmentSchemeController@updateScheme')->name('scheme.update');
    Route::post('/scheme/save/{id?}', 'Admin\PricingInvestmentSchemeController@saveScheme')->name('scheme.save');
    Route::post('/scheme/status', 'Admin\PricingInvestmentSchemeController@updateSchemeStatus')->name('scheme.status');

    Route::get('banks', 'Admin\FundedBankController@index')->name('banks');

});

Route::name('admin.settings.investment.')->middleware(['admin'])->prefix('admin/settings/investment')->group(function () {
    Route::get('/apps', 'Admin\SettingsController@appsSettings')->name('apps');
    Route::post('/save', 'Admin\SettingsController@saveSettings')->name('save');
});


Route::name('user.pricing.investment.')->middleware(['user'])->prefix('pricing')->group(function () {
    Route::get('/fund', 'User\PricingInvestmentController@index')->name('dashboard');
    Route::get('/fund/plans', 'User\PricingInvestmentController@planList')->name('plans');
    Route::get('/fund/plan/{id}', 'User\PricingInvestmentController@investmentDetails')->name('details');
    Route::get('/fund/plan/show/{id}', 'User\PricingInvestmentController@showInvestmentDetails')->name('show.details');
    Route::get('/fund/history/{type?}', 'User\PricingInvestmentController@investmentHistory')->name('history');
    Route::get('/fund/transactions/{type?}', 'User\PricingInvestmentController@transactionList')->name('transactions');
    Route::post('plan/migrate/{id?}', 'User\PricingInvestmentController@migrateInvestment')->name('plan.migrate');
    Route::post('plan/certificate/{id?}', 'User\PricingInvestmentController@planCertificate')->name('plan.certificate');
    Route::post('get/discount/{id?}', 'User\PricingInvestmentController@getDiscountByCode')->name('get.discount.by.code');
    Route::post('add/referral/{id?}', 'User\PricingInvestmentController@addReferral')->name('add.referral');
    Route::get('plan/certificate/download/{id?}', 'User\PricingInvestmentController@planCertificateDownload')->name('plan.certificate.download');

    Route::post('/invest/{ucode?}', 'User\PricingInvestController@showPlans')->name('invest');
    Route::post('/invest/funded/preview', 'User\PricingInvestController@previewInvest')->name('invest.preview');
    Route::post('/invest/confirm', 'User\PricingInvestController@confirmInvest')->name('invest.confirm');
    Route::post('/invest/cancel/{id}', 'User\PricingInvestController@cancelInvestment')->name('invest.cancel');
    Route::post('/invest/show/data', 'User\PricingInvestController@showData')->name('show.data');
    Route::post('/invest/show/data/invest', 'User\PricingInvestController@showDataInvest')->name('show.data.invest');

    Route::get('/fund/payout', 'User\PricingInvestmentController@payoutInvest')->name('payout');
    Route::post('/fund/payout/proceed', 'User\PricingInvestmentController@payoutProceed')->name('payout.proceed');

//    Route::post('/available/balance/transfer', 'User\PricingInvestmentController@transferAvailableBalance')->name('available.balance.transfer');
//    Route::post('/external/transfer', 'User\PricingInvestmentController@externalTransferBalance')->name('external.transfer');
    Route::post('/forex-trading/wallet/transfer', 'User\PricingInvestmentController@forexTradingTransfer')->name('forex-trading.wallet.transfer');
    Route::post('/investments/merge', 'User\PricingInvestmentController@mergeInvestments')->name('merge');
    Route::post('/investments/convert/orfin', 'User\PricingInvestmentController@convertToOrfin')->name('convert.orfin');
    Route::post('get/mergeable/plans', 'User\PricingInvestmentController@mergeablePlans')->name('mergeable.plans');
});