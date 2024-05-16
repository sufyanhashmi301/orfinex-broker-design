<?php

use App\Http\Controllers\Backend\AdvertisementMaterialController;
use App\Http\Controllers\Backend\AppController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\BlogController;
use App\Http\Controllers\Backend\BlackListCountryController;
use App\Http\Controllers\Backend\CustomCssController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\DepositController;
use App\Http\Controllers\Backend\EmailTemplateController;
use App\Http\Controllers\Backend\GatewayController;
use App\Http\Controllers\Backend\IBSchemaController;
use App\Http\Controllers\Backend\ImportController;
use App\Http\Controllers\Backend\InvestmentController;
use App\Http\Controllers\Backend\KycController;
use App\Http\Controllers\Backend\LanguageController;
use App\Http\Controllers\Backend\LevelReferralController;
use App\Http\Controllers\Backend\LinkController;
use App\Http\Controllers\Backend\NavigationController;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\PluginController;
use App\Http\Controllers\Backend\ProfitController;
use App\Http\Controllers\Backend\ProfitDeductionController;
use App\Http\Controllers\Backend\RankingController;
use App\Http\Controllers\Backend\ReferralController;
use App\Http\Controllers\Backend\RiskProfileTagController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\ScheduleController;
use App\Http\Controllers\Backend\ForexSchemaController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\SmsController;
use App\Http\Controllers\Backend\SocialController;
use App\Http\Controllers\Backend\StaffController;
use App\Http\Controllers\Backend\ThemeController;
use App\Http\Controllers\Backend\TicketController;
use App\Http\Controllers\Backend\TransactionController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\WithdrawController;
use App\Http\Controllers\Backend\IBController;
use App\Http\Controllers\Backend\SecurityController;
use App\Http\Controllers\Backend\ChallengeController;
use App\Http\Controllers\Backend\InvestDashboardController;
use App\Http\Controllers\Backend\PricingInvestedPlansController;
use App\Http\Controllers\Backend\PricingInvestmentSchemeController;
use App\Http\Controllers\Backend\LedgerProfitsController;
use App\Http\Controllers\Backend\FundedBankController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Admin Dashboard
Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');

//===============================  Customer Management ==================================
Route::resource('user', UserController::class)->only('index', 'edit', 'update', 'destroy');
Route::group(['prefix' => 'user', 'as' => 'user.', 'controller' => UserController::class], function () {
    Route::get('active', 'activeUser')->name('active');
    Route::get('disabled', 'disabled')->name('disabled');
    Route::get('login/{id}', 'userLogin')->name('login');
    Route::post('status-update/{id}', 'statusUpdate')->name('status-update');
    Route::post('password-update/{id}', 'passwordUpdate')->name('password-update');
    Route::post('balance-update/{id}', 'balanceUpdate')->name('balance-update');
    Route::get('mail-send/all', 'mailSendAll')->name('mail-send.all');
    Route::post('mail-send', 'mailSend')->name('mail-send');
    Route::get('transaction/{id}', 'transaction')->name('transaction');
    Route::get('ib-info/{id}', 'ibInfo')->name('ib-info');
});
Route::name('investment.')->prefix('admin/investment')->group(function () {
    Route::get('/dashboard', 'Admin\InvestDashboardController@index')->name('dashboard');;

    Route::get('/history/{status?}', 'Admin\InvestedPlansController@investedPlanList')->name('list');
    Route::get('/plan/details/{id}', 'Admin\InvestedPlansController@showInvestmentDetails')->name('details');
    Route::get('/plan/action', 'Admin\InvestedPlansController@showInvestmentDetails')->name('plan.action');
    Route::post('plan/approve/{id?}', 'Admin\InvestedPlansController@approveInvestment')->name('plan.approve');
    Route::post('plan/cancel/{id?}', 'Admin\InvestedPlansController@cancelInvestment')->name('plan.cancel');
    Route::post('plan/migrate/{id?}', 'Admin\InvestedPlansController@migrateInvestment')->name('plan.migrate');

    Route::get('/profits/{type?}', 'Admin\LedgerProfitsController@profitList')->name('profits.list');
    Route::get('/transactions/{type?}', 'Admin\LedgerProfitsController@transactionList')->name('transactions.list');

    Route::post('/payout/profits', 'Admin\LedgerProfitsController@profitsPayout')->name('profits.payout');

    // Schemes
    Route::get('/schemes/{status?}', 'Admin\InvestmentSchemeController@schemeList')->name('schemes');
    Route::get('/scheme/{action?}', 'Admin\InvestmentSchemeController@actionScheme')->name('scheme.action');
    Route::post('/scheme/update/{id?}', 'Admin\InvestmentSchemeController@updateScheme')->name('scheme.update');
    Route::post('/scheme/status', 'Admin\InvestmentSchemeController@updateSchemeStatus')->name('scheme.status');

    //********************* Tokens ********************************
    Route::get('token/history/{status?}', 'Admin\TokenInvestedPlansController@investedPlanList')->name('token.list');
    Route::get('token/plan/details/{id}', 'Admin\TokenInvestedPlansController@showInvestmentDetails')->name('token.details');
    Route::get('token/plan/action', 'Admin\TokenInvestedPlansController@showInvestmentDetails')->name('token.plan.action');
    Route::post('token/plan/approve/{id?}', 'Admin\TokenInvestedPlansController@approveInvestment')->name('token.plan.approve');
    Route::post('token/plan/cancel/{id?}', 'Admin\TokenInvestedPlansController@cancelInvestment')->name('token.plan.cancel');
    Route::post('token/plan/migrate/{id?}', 'Admin\TokenInvestedPlansController@migrateInvestment')->name('token.plan.migrate');

    Route::get('token/profits/{type?}', 'Admin\TokenLedgerProfitsController@profitList')->name('token.profits.list');
    Route::get('token/transactions/{type?}', 'Admin\TokenLedgerProfitsController@transactionList')->name('token.transactions.list');

    Route::post('token/payout/profits', 'Admin\TokenLedgerProfitsController@profitsPayout')->name('token.profits.payout');

    // Schemes
    Route::get('token/schemes/{status?}', 'Admin\TokenInvestmentSchemeController@schemeList')->name('token.schemes');
    Route::get('token/scheme/{action?}', 'Admin\TokenInvestmentSchemeController@actionScheme')->name('token.scheme.action');
    Route::post('token/scheme/update/{id?}', 'Admin\TokenInvestmentSchemeController@updateScheme')->name('token.scheme.update');
    Route::post('token/scheme/status', 'Admin\TokenInvestmentSchemeController@updateSchemeStatus')->name('token.scheme.status');


});

Route::name('pricing.investment.')->prefix('admin/pricing/investment')->group(function () {
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
    Route::get('/schemes/{status?}', 'Admin\InvestmentSchemeController@schemeList')->name('schemes');
    Route::get('/scheme/{action?}', 'Admin\InvestmentSchemeController@actionScheme')->name('scheme.action');
    Route::post('/scheme/update/{id?}', 'Admin\InvestmentSchemeController@updateScheme')->name('scheme.update');
    Route::post('/scheme/status', 'Admin\InvestmentSchemeController@updateSchemeStatus')->name('scheme.status');

    Route::get('banks', 'Admin\FundedBankController@index')->name('banks');

});

Route::resource('kyc-form', KycController::class);
Route::group(['prefix' => 'kyc', 'as' => 'kyc.', 'controller' => KycController::class], function () {
    Route::get('pending', 'KycPending')->name('pending');
    Route::get('rejected', 'KycRejected')->name('rejected');
    Route::get('action/{id}', 'depositAction')->name('action');
    Route::post('action-now', 'actionNow')->name('action.now');
    Route::get('all', 'kycAll')->name('all');
});
Route::resource('risk-profile-tag', RiskProfileTagController::class);
Route::group(['prefix' => 'risk-profile-tag', 'as' => 'risk-profile-tag.', 'controller' => RiskProfileTagController::class], function () {
    Route::post('tag/update/{id}', 'tagsUpdate')->name('tag.update');
    Route::post('tag/delete/{id}', 'tagDelete')->name('tag.delete');
});

Route::resource('ib-form', IBController::class);
Route::group(['prefix' => 'ib', 'as' => 'ib.', 'controller' => IBController::class], function () {
    Route::get('pending', 'IbPendingList')->name('pending.list');
    Route::get('approved', 'IbApprovedList')->name('approved.list');
    Route::get('rejected', 'IbRejectedList')->name('rejected.list');
    Route::get('all', 'ibAllList')->name('all.list');
    Route::get('answer/view/{user}', 'answerView')->name('answer.view');
    Route::post('approve', 'approveIbMember')->name('approve');
    Route::post('update', 'updateIbMember')->name('update');
    Route::post('reject', 'rejectIbMember')->name('reject');
    Route::post('save/form', 'saveForm')->name('save.form');
});

//===============================  Role Management ==================================
Route::resource('roles', RoleController::class)->except('show', 'destroy');
Route::resource('staff', StaffController::class)->except('show', 'destroy', 'create');

//===============================  Plans Management ==================================
Route::resource('schedule', ScheduleController::class)->except('show', 'destroy', 'create');
Route::resource('accountType', ForexSchemaController::class)->except('show', 'destroy');
Route::resource('ibAccountType', IBSchemaController::class)->except('show', 'destroy');
Route::resource('blackListCountry', BlackListCountryController::class)->except('show');

//===============================  Profit Deduction Management ==================================
Route::get('profit/deduction', [ProfitDeductionController::class, 'index'])->name('profit.deduction.index');
Route::post('profit/deduction/store', [ProfitDeductionController::class, 'store'])->name('profit.deduction.store');

//===============================  Transactions ==================================
Route::get('transactions/{id?}', [TransactionController::class, 'transactions'])->name('transactions');
Route::get('investments/{id?}', [InvestmentController::class, 'investments'])->name('investments');
Route::get('forex-accounts/real/{id?}', [InvestmentController::class, 'forexAccountsReal'])->name('forex-accounts-real');
Route::get('forex-accounts/demo/id?}', [InvestmentController::class, 'forexAccountsDemo'])->name('forex-accounts-demo');
Route::get('all-profits/{id?}', [ProfitController::class, 'allProfits'])->name('all-profits');

//===============================  Essentials ==================================

Route::group(['prefix' => 'gateway', 'as' => 'gateway.', 'controller' => GatewayController::class], function () {
    Route::get('/automatic', 'automatic')->name('automatic');
    Route::post('update/{id}', 'update')->name('update')->withoutMiddleware('XSS');
    Route::get('currency/{gateway_id}', 'gatewayCurrency')->name('supported.currency');
});
Route::group(['prefix' => 'deposit', 'as' => 'deposit.', 'controller' => DepositController::class], function () {
    //=============================== deposit Method ================================
    Route::group(['prefix' => 'method', 'as' => 'method.'], function () {
        Route::get('list/{type}', 'methodList')->name('list');
        Route::get('create/{type}', 'createMethod')->name('create');
        Route::post('store', 'methodStore')->name('store')->withoutMiddleware('XSS');
        Route::get('edit/{type}', 'methodEdit')->name('edit');
        Route::post('update/{id}', 'methodUpdate')->name('update')->withoutMiddleware('XSS');
    });
    //=============================== end deposit Method ================================

    Route::get('manual-pending', 'pending')->name('manual.pending');
    Route::get('history', 'history')->name('history');
    Route::get('action/{id}', 'depositAction')->name('action');
    Route::post('action-now', 'actionNow')->name('action.now');
});
Route::group(['prefix' => 'withdraw', 'as' => 'withdraw.', 'controller' => WithdrawController::class], function () {
    //=============================== withdraw Method ================================

    Route::group(['prefix' => 'method', 'as' => 'method.'], function () {
        Route::get('list/{type}', 'methods')->name('list');
        Route::get('create/{type}', 'methodCreate')->name('create');
        Route::post('store', 'methodStore')->name('store')->withoutMiddleware('XSS');
        Route::get('edit/{type}', 'methodEdit')->name('edit');
        Route::post('update/{id}', 'methodUpdate')->name('update')->withoutMiddleware('XSS');
    });

    //Schedule
    Route::get('schedule', 'schedule')->name('schedule');
    Route::post('schedule-update', 'scheduleUpdate')->name('schedule.update');

    Route::get('history', 'history')->name('history');
    Route::get('pending', 'pending')->name('pending');

    Route::get('action/{id}', 'withdrawAction')->name('action');
    Route::post('action-now', 'actionNow')->name('action.now');
});
Route::group(['prefix' => 'referral', 'as' => 'referral.', 'controller' => ReferralController::class], function () {
    Route::get('index', 'index')->name('index');
    Route::post('store', 'store')->name('store');
    Route::post('update', 'update')->name('update');
    Route::post('delete', 'delete')->name('delete');

    Route::get('direct/list/{id}', 'directList')->name('direct.list');
    Route::post('direct/add', 'addDirectReferral')->name('direct.add');
    Route::delete('direct/delete', 'deleteDirectReferral')->name('direct.delete');
    Route::get('target', 'target')->name('target');
    Route::post('target-store', 'targetStore')->name('target-store');
    Route::post('target-update', 'targetUpdate')->name('target-update');

    //level referral
    Route::resource('level', LevelReferralController::class)->except('create', 'show', 'edit');
    Route::post('level-status', [LevelReferralController::class, 'statusUpdate'])->name('level-status');
});
//===============================  Advertisement Material ==================================
Route::resource('advertisement_material', AdvertisementMaterialController::class)->except('show', 'destroy');

Route::resource('ranking', RankingController::class)->only('index', 'store', 'update');

//===============================  Site Essentials ==================================

Route::group(['prefix' => 'theme', 'as' => 'theme.', 'controller' => ThemeController::class], function () {

    Route::get('global', 'globalSettings')->name('global');
    Route::get('site', 'siteTheme')->name('site');
    Route::get('dynamic-landing', 'dynamicLanding')->name('dynamic-landing');

    Route::get('status-update', 'statusUpdate')->name('status-update');

    Route::post('dynamic-landing-update', 'dynamicLandingUpdate')->name('dynamic-landing-update');
    Route::get('dynamic-landing-status-update', 'dynamicLandingStatusUpdate')->name('dynamic-landing-status-update');
    Route::post('dynamic-landing-delete/{id}', 'dynamicLandingDelete')->name('dynamic-landing-delete');
});

Route::group(['prefix' => 'navigation', 'as' => 'navigation.', 'controller' => NavigationController::class], function () {
    Route::get('menu', 'index')->name('menu');
    Route::post('menu-add', 'store')->name('menu.add');
    Route::get('menu-edit/{id}', 'edit')->name('menu.edit');
    Route::post('menu-update', 'update')->name('menu.update');
    Route::post('menu-delete', 'delete')->name('menu.delete');
    Route::get('menu-delete/{id}/{type}', 'typeDelete')->name('menu.type.delete');
    Route::post('menu-position-update', 'positionUpdate')->name('position.update');

    Route::get('header', 'header')->name('header');
    Route::get('footer', 'footer')->name('footer');

    Route::get('translate/{id}', 'translate')->name('translate');
    Route::post('translate', 'translateNow')->name('translate.now');
});
Route::group(['prefix' => 'page', 'as' => 'page.', 'controller' => PageController::class], function () {
    Route::get('create', 'create')->name('create');
    Route::post('store', 'store')->name('store')->withoutMiddleware('XSS');
    Route::get('edit/{name}', 'edit')->name('edit');
    Route::post('update', 'update')->name('update')->withoutMiddleware('XSS');
    Route::post('delete/now', 'deleteNow')->name('delete.now');

    Route::get('section/{section}', 'landingSection')->name('section.section');
    Route::post('section/update', 'landingSectionUpdate')->name('section.section.update');
    Route::post('content-store', 'contentStore')->name('content-store');
    Route::get('content-edit/{id}', 'contentEdit')->name('content-edit');
    Route::post('content-update', 'contentUpdate')->name('content-update');
    Route::post('content-delete', 'contentDelete')->name('content-delete');

    Route::resource('blog', BlogController::class)->except('show')->withoutMiddleware('XSS');

    Route::get('settings', 'pageSetting')->name('setting');
    Route::post('setting-update', 'pageSettingUpdate')->name('setting.update');
});
Route::get('footer-content', [PageController::class, 'footerContent'])->name('footer-content');

Route::group(['prefix' => 'social', 'as' => 'social.', 'controller' => SocialController::class], function () {
    Route::post('store', 'store')->name('store');
    Route::post('update', 'update')->name('update');
    Route::post('delete', 'delete')->name('delete');
    Route::post('position-update', 'positionUpdate')->name('position.update');
});

//===============================  site Settings ==================================
Route::group(['prefix' => 'settings', 'as' => 'settings.', 'controller' => SettingController::class], function () {
    Route::get('site', 'siteSetting')->name('site');
    Route::get('mail', 'mailSetting')->name('mail');
    Route::post('mail-connection-test', 'mailConnectionTest')->name('mail.connection.test');
    Route::post('update', 'update')->name('update');

    Route::get('plugin/{name}', [PluginController::class, 'plugin'])->name('plugin');
    Route::get('plugin-data/{id}', [PluginController::class, 'pluginData'])->name('plugin.data');
    Route::post('plugin-update/{id}', [PluginController::class, 'update'])->name('plugin.update');

    //notification tune
    Route::group(['prefix' => 'notification', 'as' => 'notification.', 'controller' => NotificationController::class], function () {
        Route::get('tune', 'setTune')->name('tune');
        Route::get('tune/status/{id}', 'status')->name('tune.status');
    });
});

//===============================  Security Settings ==================================
Route::group(['prefix' => 'security', 'as' => 'security.', 'controller' => SecurityController::class], function () {
    Route::get('all-sections', 'allSections')->name('all-sections');
    Route::get('blocklist-ip', 'blocklistIP')->name('blocklist-ip');
    Route::get('single-session', 'singleSession')->name('single-session');
    Route::get('blocklist-email', 'blocklistEmail')->name('blocklist-email');
    Route::get('login-expiry', 'loginExpiry')->name('login-expiry');
});

// show all notifications
Route::get('notification/all', [NotificationController::class, 'all'])->name('notification.all');
Route::get('latest-notification', [NotificationController::class, 'latestNotification'])->name('latest-notification');
Route::get('notification-read/{id}', [NotificationController::class, 'readNotification'])->name('read-notification');

Route::resource('language', LanguageController::class);
Route::get('language-keyword/{language}', [LanguageController::class, 'languageKeyword'])->name('language-keyword');
Route::post('language-keyword-update', [LanguageController::class, 'keywordUpdate'])->name('language-keyword-update');
Route::get('language-sync-missing', [LanguageController::class, 'syncMissing'])->name('language-sync-missing');

Route::get('email-template', [EmailTemplateController::class, 'index'])->name('email-template');
Route::get('email-template-edit/{id}', [EmailTemplateController::class, 'edit'])->name('email-template-edit');
Route::post('email-template-update', [EmailTemplateController::class, 'update'])->name('email-template-update');

Route::group(['prefix' => 'template', 'as' => 'template.'], function () {
    Route::group(['prefix' => 'sms', 'as' => 'sms.', 'controller' => SmsController::class], function () {
        Route::get('/', 'template')->name('index');
        Route::get('template-edit/{id}', 'edit_template')->name('template-edit');
        Route::post('template-update', 'update_template')->name('template-update');
    });

    Route::group(['prefix' => 'notification', 'as' => 'notification.', 'controller' => NotificationController::class], function () {
        Route::get('/', 'template')->name('index');
        Route::get('template-edit/{id}', 'editTemplate')->name('template-edit');
        Route::post('template-update', 'updateTemplate')->name('template-update');
    });
});

//===============================  Links Settings ==================================
Route::group(['prefix' => 'links', 'as' => 'links.', 'controller' => LinkController::class], function () {
    Route::get('document-links', 'documentLinks')->name('document-links');
    Route::get('platform-links', 'platformLinks')->name('platform-links');
});

//===============================  Others ==================================
Route::group(['controller' => AppController::class], function () {
    Route::get('subscribers', 'subscribers')->name('subscriber');
    Route::get('mail-send-subscriber', 'mailSendSubscriber')->name('mail.send.subscriber');
    Route::post('mail-send-subscriber-now', 'mailSendSubscriberNow')->name('mail.send.subscriber.now');
});
Route::group(['prefix' => 'support-ticket', 'as' => 'ticket.', 'controller' => TicketController::class], function () {
    Route::get('index/{id?}', 'index')->name('index');
    Route::post('reply', 'reply')->name('reply');
    Route::get('show/{uuid}', 'show')->name('show');
    Route::get('close-now/{uuid}', 'closeNow')->name('close.now');
});
Route::get('custom-css', [CustomCssController::class, 'customCss'])->name('custom-css');
Route::post('custom-css-update', [CustomCssController::class, 'customCssUpdate'])->name('custom-css.update');

//admin self manage
Route::get('profile', [AppController::class, 'profile'])->name('profile');
Route::post('profile-update', [AppController::class, 'profileUpdate'])->name('profile-update');

Route::get('password-change', [AppController::class, 'passwordChange'])->name('password-change');
Route::post('password-update', [AppController::class, 'passwordUpdate'])->name('password-update');

Route::get('application-info', [AppController::class, 'applicationInfo'])->name('application-info');
Route::get('clear-cache', [AppController::class, 'clearCache'])->name('clear-cache');

Route::post('logout', [AuthController::class, 'logout'])->name('logout')->withoutMiddleware('isDemo');

Route::get('/ib-resources', function () {
    return view('backend.ib.resources.index');
});


Route::group(['prefix' => 'pricing', 'as' => 'pricing.', 'controller' => PricingInvestedPlansController::class], function () {
    Route::get('/dashboard', 'index')->name('dashboard');;

    Route::get('/history/{status?}', 'investedPlanList')->name('list');
    Route::get('/plan/details/{id}', 'showInvestmentDetails')->name('details');
    Route::get('/plan/action', 'showInvestmentDetails')->name('plan.action');
    Route::post('plan/approve/{id?}', 'approveInvestment')->name('plan.approve');
    Route::post('plan/cancel/{id?}', 'cancelInvestment')->name('plan.cancel');
    Route::post('plan/migrate/{id?}', 'migrateInvestment')->name('plan.migrate');
});

Route::group(['prefix' => 'pricing', 'as' => 'pricing.', 'controller' => LedgerProfitsController::class], function () {
    Route::get('/profits/{type?}', 'profitList')->name('profits.list');
    Route::get('/transactions/{type?}', 'transactionList')->name('transactions.list');

    Route::post('/payout/profits', 'profitsPayout')->name('profits.payout');
});

Route::group(['prefix' => 'pricing', 'as' => 'pricing.', 'controller' => PricingInvestmentSchemeController::class], function () {
    // Schemes
    Route::get('/schemes/{status?}', 'schemeList')->name('schemes');
    Route::get('/scheme/{action?}', 'actionScheme')->name('scheme.action');
    Route::post('/scheme/update/{id?}', 'updateScheme')->name('scheme.update');
    Route::post('/scheme/save/{id?}', 'saveScheme')->name('scheme.save');
    Route::post('/scheme/status', 'updateSchemeStatus')->name('scheme.status');

});

Route::post('banks', [FundedBankController::class, 'index'])->name('banks');

Route::get('ib-resources/new', function () {
    return view('backend.ib.resources.create');
});

Route::get('/loyalty-points', function () {
    return view('backend.loyalty_points.create');
});
Route::get('import-form', [ImportController::class, 'index'])->name('import-form');
Route::post('import', [ImportController::class, 'import'])->name('import');

Route::get('/reports', function () {
    return view('backend.reports.index');
});

Route::get('/bonus', function () {
    return view('backend.bonus.index');
});

Route::get('/bonus/create', function () {
    return view('backend.bonus.create');
});

Route::resource('challenges', ChallengeController::class)->except('show', 'destroy');

Route::resource('challenges', ChallengeController::class)->except('show', 'destroy');

Route::get('/step_rules/create', [ChallengeController::class, 'step_rules_index'])->name('step-rules');
Route::post('/step_rules/create', [ChallengeController::class, 'step_rules_create'])->name('step-rules.create');
