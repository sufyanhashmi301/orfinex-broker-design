<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KycController;
use App\Http\Controllers\AddonController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\RiskRuleController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\KycMethodController;
use App\Http\Controllers\KycNoticeController;
use App\Http\Controllers\AccountTypeController;
use App\Http\Controllers\Backend\AppController;
use App\Http\Controllers\Backend\SmsController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\LinkController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\TradingStatsController;
use App\Http\Controllers\AffiliateRuleController;
use App\Http\Controllers\Backend\StaffController;
use App\Http\Controllers\Backend\ThemeController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PayoutRequestController;
use App\Http\Controllers\Backend\ImportController;
use App\Http\Controllers\Backend\PluginController;
use App\Http\Controllers\Backend\SocialController;
use App\Http\Controllers\Backend\TicketController;
use App\Http\Controllers\Backend\TwilioController;
use App\Http\Controllers\WithdrawMethodController;
use App\Http\Controllers\AccountActivityController;
use App\Http\Controllers\AccountBalanceOperationController;
use App\Http\Controllers\Backend\CountryController;
use App\Http\Controllers\Backend\DepositController;
use App\Http\Controllers\Backend\GatewayController;
use App\Http\Controllers\Backend\RankingController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\DiscountController;
use App\Http\Controllers\Backend\LanguageController;
use App\Http\Controllers\Backend\ScheduleController;
use App\Http\Controllers\Backend\SecurityController;
use App\Http\Controllers\Backend\WithdrawController;
use App\Http\Controllers\LeaderboardBadgeController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\KYCLevelsController;
use App\Http\Controllers\Frontend\ContractController;
use App\Http\Controllers\Backend\DepartmentController;
use App\Http\Controllers\Backend\SocialLinkController;
use App\Http\Controllers\LeaderboardRankingController;
use App\Http\Controllers\Backend\DesignationController;
use App\Http\Controllers\Backend\LeaderboardController;
use App\Http\Controllers\Backend\TransactionController;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\Backend\TicketStatusController;
use App\Http\Controllers\AccountTypeInvestmentController;
use App\Http\Controllers\Backend\EmailTemplateController;
use App\Http\Controllers\Backend\TicketPriorityController;
use App\Http\Controllers\Backend\BlackListCountryController;
use App\Http\Controllers\VoiceCallController;
use App\Models\PayoutRequest;

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


Route::post('logout', [AuthController::class, 'logout'])->name('logout')->withoutMiddleware('isDemo');

Route::controller(TwilioController::class)->group(function () {
    Route::post('/outbound.xml', 'outboundXml')->name('outbound-xml');
    Route::get('/generate-token', 'generateToken')->name('generate.token');
    Route::post('/outgoing-call', 'handleOutgoingCall')->name('outgoing-call');
    Route::post('/twilio/voice', 'handleIncomingCall');
});

Route::middleware(['2fa_admin'])->group(function () {
    
    //Admin Dashboard
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');

    // Customer Management
    Route::resource('user', UserController::class)->only('index', 'edit', 'update', 'destroy');
    Route::group(['prefix' => 'user', 'as' => 'user.', 'controller' => UserController::class], function () {
        Route::get('active', 'activeUser')->name('active');
        Route::get('disabled', 'disabled')->name('disabled');
        Route::get('login/{id}', 'userLogin')->name('login');
        Route::post('status-update/{id}', 'statusUpdate')->name('status-update');
        Route::post('password-update/{id}', 'passwordUpdate')->name('password-update');
        Route::get('mail-send/all', 'mailSendAll')->name('mail-send.all');
        Route::post('mail-send', 'mailSend')->name('mail-send');
        Route::get('transaction/{id}', 'transaction')->name('transaction');
        Route::post('export/{type?}', 'export')->name('export');
        Route::post('note/create/{id}', 'createNote')->name('note.add');
        Route::get('create', 'createCustomer')->name('create');
        Route::post('store', 'store')->name('store');
        Route::post('kyc/{id}', 'kyc')->name('kyc');
    });

    //===============================  Twilio ==================================
    Route::controller(TwilioController::class)->group(function () {
        Route::get('/call', 'index')->name('twilio.index');
        // Route::get('/call', 'voice')->name('twilio.voice');
        Route::post('/make-call', 'initiateCall')->name('initiate.call');
        Route::post('/end-call', 'endCall')->name('end.call');
        Route::post('/mute-microphone', 'muteMicrophone')->name('mute.microphone');
        Route::post('/insert-call-history', 'insertCallHistory')->name('insert.call.history');
        Route::post('/insert-new-customer', 'insertNewCustomer')->name('insert.new.customer');
        Route::get('/admin/user-report', 'userReport')->name('user.report');
        Route::get('/call-history', 'getWeeklyCallReport')->name('get.call.history');
        Route::get('/play-call-recording/{callSid}', 'playCallRecording');
        Route::get('/player-iframe/{callSid}', 'playerIframe');
        Route::get('/recording/{recordingSid}', 'streamRecording')->name('recording.stream');
    });


    //===============================  Role Management ==================================
    Route::resource('roles', RoleController::class)->except('show', 'destroy');
    Route::delete('roles/{roleId}', [RoleController::class, 'destroy'])->name('role.delete');
    Route::resource('staff', StaffController::class)->except('show', 'destroy');
    Route::delete('staff/{staffId}', [StaffController::class, 'destroy'])->name('staff.delete');
    Route::get('staff/security/{id}', [StaffController::class, 'security'])->name('staff.security');
    Route::get('staff/2fa', [StaffController::class, 'twoFa'])->name('staff.2fa');
    Route::post('staff/action-2fa', [StaffController::class, 'actionTwoFa'])->name('staff.action-2fa');
    Route::post('/2fa/verify', function () {
        return redirect(route('admin.dashboard'));
    })->name('2fa.verify');
    
    Route::resource('schedule', ScheduleController::class)->except('show', 'destroy', 'create');


    // =============================== Optimization ===============================
    // Account Types
    Route::get('account-type/info', [AccountTypeController::class, 'accountTypeInfo'])->name('account_type.info');
    Route::resource('account-type', AccountTypeController::class);

    // Accounts (aka Investments)
    Route::get('/accounts', [AccountTypeInvestmentController::class, 'adminIndex'])->name('accounts.index');
    Route::post('account/manual', [AccountTypeInvestmentController::class, 'addManually'])->name('account.add_manually');
    Route::get('account/trading-stats/history', [TradingStatsController::class, 'accountTradingStatsHistory'])->name('account.trading_stats.history');
    Route::get('account/trading-stats/{account_id}', [TradingStatsController::class, 'adminTradingStats'])->name('account.trading_stats');
    Route::post('account/config', [AccountTypeController::class, 'config'])->name('account_type.config');
    Route::post('account/restore-violated-account/{id}', [AccountTypeInvestmentController::class, 'restoreViolatedAccount'])->name('account.restore_violated_account');

    // Account Balance Operation
    Route::post('account/balance-operation', [AccountBalanceOperationController::class, 'update'])->name('account.balance-operation.update');

    // Banner Settings 
    Route::get('banner/user-dashboard/', [BannerController::class, 'userDashboard'])->name('banner.user_dashboard');
    Route::post('banner/store', [BannerController::class, 'store'])->name('banner.store');
    Route::post('banner/update/{id}', [BannerController::class, 'update'])->name('banner.update');

    // Slider Settings
    Route::get('slider/user-dashboard/', [SliderController::class, 'userDashboard'])->name('slider.user_dashboard');
    Route::post('slider/store', [SliderController::class, 'store'])->name('slider.store');
    Route::get('slider/update/{id}', [SliderController::class, 'update'])->name('slider.update');
    Route::delete('slider/delete/{id}', [SliderController::class, 'destroy'])->name('slider.destroy');

    // Account Activity
    Route::get('accounts-activity-log', [AccountActivityController::class, 'index'])->name('accounts_activity.log');
    Route::get('phase-approval-request/{investment_id}', [AccountActivityController::class, 'phaseApprovalRequest'])->name('account-phase.approval-request');

    // leaderboards
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::post('/leaderboard-badges/store', [LeaderboardBadgeController::class, 'store'])->name('leaderboard-badge.store');
    Route::post('/leaderboard-rankings/store', [LeaderboardRankingController::class, 'store'])->name('leaderboard-rankings.store');
    Route::get('/leaderboard-rankings/create', [LeaderboardRankingController::class, 'create'])->name('leaderboard-rankings.create');
    Route::delete('/leaderboard-rankings/delete/{leaderboardRanking}', [LeaderboardRankingController::class, 'destroy'])->name('leaderboard-rankings.delete');

    // Affiliates
    Route::get('/affiliate-rules', [AffiliateRuleController::class, 'index'])->name('affiliate-rules.index');
    Route::get('/affiliates', [AffiliateController::class, 'index'])->name('affiliates.index');
    Route::get('/affiliate-rules/create', [AffiliateRuleController::class, 'create'])->name('affiliate-rules.create');
    Route::post('/affiliate-rules/store', [AffiliateRuleController::class, 'store'])->name('affiliate-rules.store');

    // Payout Requests
    Route::get('/payout-requests', [PayoutRequestController::class, 'index'])->name('payout_requests.index');
    Route::get('/payout-request/{payout_request_id}', [PayoutRequestController::class, 'action'])->name('payout_request.action');
    Route::post('/payout-request/setting', [PayoutRequestController::class, 'config'])->name('payout_requests.config');

    // Risk Rules
    Route::group(['prefix' => 'risk-rule', 'as' => 'risk-rule.', 'controller' => RiskRuleController::class], function () {
        Route::get('/quick-trades', 'riskRule')->name('quick_trades');
        Route::get('/scalper-detection', 'riskRule')->name('scalper_detection');
        Route::get('/most-trades', 'riskRule')->name('most_trades');
        Route::get('/ip-address-info', 'riskRule')->name('ip_address');
        Route::get('/trade-age', 'riskRule')->name('trade_age');
        Route::get('/open-positions', 'riskRule')->name('open_positions');

        Route::post('/update-risk-criteria', 'updateRiskCriteria')->name('update.risk_criteria');
    });

    // Certificates
    Route::get('/certificates/config', [CertificateController::class, 'manage'])->name('certificates.manage');
    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::post('/update-certificate/{id}', [CertificateController::class, 'update'])->name('certificate.update');
    Route::get('/view-certificate/{id}', [CertificateController::class, 'viewCertificate'])->name('view_certificate');
    Route::post('/certificate-config/update', [CertificateController::class, 'updateConfig'])->name('certificate_config.update');

    // Contracts
    Route::get('/contracts', [ContractController::class, 'adminIndex'])->name('contracts.index');
    Route::get('/contract/show/{id}', [ContractController::class, 'adminShow'])->name('contract.show');
    Route::post('/contract/config', [ContractController::class, 'config'])->name('contract.config');
    Route::post('/contract/mark-as', [ContractController::class, 'markContractAs'])->name('contract.mark_as');

    // Contract Template
    // Route::get('/contract/template', [])

    // Addons
    Route::get('/addons', [AddonController::class, 'index'])->name('addons.index');
    Route::post('/addon', [AddonController::class, 'update'])->name('addon.update');

    // Invoice
    Route::get('/invoice/{id}', [InvoiceController::class, 'show'])->name('invoice.show');

    // KYC Manage
    Route::get('kycs', [KycController::class, 'index'])->name('kyc.index');
    Route::get('kyc/data/{id}', [KycController::class, 'data'])->name('kyc.data');
    Route::post('/kyc/action', [KycController::class, 'action'])->name('kyc.action');

    // KYC Settings
    Route::get('/settings/kyc-methods', [KycMethodController::class, 'index'])->name('settings.kyc');
    Route::post('/settings/kyc-methods/store', [KycMethodController::class, 'store'])->name('kyc_method.store');
    Route::post('/settings/kyc-methods/update/{id}', [KycMethodController::class, 'update'])->name('kyc_method.update');
    Route::get('/settings/kyc-methods/{option_name}', [KycMethodController::class, 'deleteManualMethodField'])->name('kyc_method.delete_manual_method_option');
    Route::get('/settings/kyc-methods/option-toggle/{action}', [KycMethodController::class, 'optionToggle'])->name('kyc_method.option_toggle');
    Route::get('/settings/kyc-methods/method-toggle/{id}', [KycMethodController::class, 'methodToggle'])->name('kyc_method.method_toggle');
    Route::post('/settings/kyc-notice', [KycNoticeController::class, 'updateSettings'])->name('kyc_notice.update');

    // Payment Methods
    Route::get('payment-methods/{type}', [PaymentMethodController::class, 'index'])->name('payment-method.index');
    Route::group(['prefix' => 'payment-method', 'as' => 'payment-method.', 'controller' => PaymentMethodController::class], function () {
        Route::get('create/{type}', 'create')->name('create');
        Route::post('store', 'store')->name('store')->withoutMiddleware('XSS');
        Route::get('edit/{type}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update')->withoutMiddleware('XSS');
    });

    // Withdraw Methods
    Route::get('withdraw-methods/{type}', [WithdrawMethodController::class, 'index'])->name('withdraw-method.index');
    Route::group(['prefix' => 'withdraw-method', 'as' => 'withdraw-method.', 'controller' => WithdrawMethodController::class], function () {
        Route::get('create/{type}', 'create')->name('create');
        Route::post('store', 'store')->name('store')->withoutMiddleware('XSS');
        Route::get('edit/{type}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update')->withoutMiddleware('XSS');
    });
    
    // Storage Controller
    Route::group(['prefix' => 'settings/storage', 'as' => 'settings.storage.', 'controller' => StorageController::class], function () {
        Route::get('', 'index')->name('index');
        Route::post('update', 'update')->name('update');
        Route::get('toggle-method/{id}', 'toggleMethod')->name('toggle_method');
        Route::post('test-aws-s3', 'testAWS')->name('test_aws');
    });

    // Voice Call
    Route::group(['prefix' => 'settings/voice-call', 'as' => 'settings.voice_call.', 'controller' => VoiceCallController::class], function () {
        Route::get('', 'index')->name('index');
        Route::post('update', 'update')->name('update');
        Route::get('toggle-method/{id}', 'toggleMethod')->name('toggle_method');
    });

    // Discounts
    Route::resource('discounts', DiscountController::class);
    Route::post('discount/levels', [DiscountController::class, 'updateLevels'])->name('discount.levels.update');

    // User
    Route::get('/export-users', [UserController::class, 'exportCsv'])->name('export.all_users');

    // =============================== Optimization ===============================
    
    Route::resource('blackListCountry', BlackListCountryController::class)->except('show');
    

    //===============================  Transactions ==================================
    Route::get('transactions/{id?}', [TransactionController::class, 'transactions'])->name('transactions');
    Route::post('transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
    Route::get('transactions/view/{id}', [TransactionController::class, 'view'])->name('transactions.view');


    //===============================  Essentials ==================================
    Route::group(['prefix' => 'gateway', 'as' => 'gateway.', 'controller' => GatewayController::class], function () {
        Route::get('/automatic', 'automatic')->name('automatic');
        Route::post('update/{id}', 'update')->name('update')->withoutMiddleware('XSS');
        Route::get('currency/{gateway_id}', 'gatewayCurrency')->name('supported.currency');
    });

    //=============================== deposit Method ================================
    Route::group(['prefix' => 'deposit', 'as' => 'deposit.', 'controller' => DepositController::class], function () {

        Route::get('pending-payments', 'pending')->name('manual.pending');
        Route::get('history', 'history')->name('history');
        Route::post('export',  'export')->name('export');
        Route::get('action/{id}', 'depositAction')->name('action');
        Route::post('action-now', 'actionNow')->name('action.now');
    });

    //=============================== withdraw Method ================================
    Route::group(['prefix' => 'withdraw', 'as' => 'withdraw.', 'controller' => WithdrawController::class], function () {

        //Schedule
        Route::get('schedule', 'schedule')->name('schedule');
        Route::post('schedule-update', 'scheduleUpdate')->name('schedule.update');
        Route::post('export',  'export')->name('export');
        Route::get('history', 'history')->name('history');
        Route::get('pending', 'pending')->name('pending');
        Route::post('pending/export', 'pendingExport')->name('pending.export');
        Route::get('action/{id}', 'withdrawAction')->name('action');
        Route::post('action-now', 'actionNow')->name('action.now');

    });

    // Ranking
    Route::resource('ranking', RankingController::class)->only('index', 'store', 'update');


    //===============================  Site Essentials ==================================
    Route::group(['prefix' => 'theme', 'as' => 'theme.', 'controller' => ThemeController::class], function () {

        Route::get('site', 'siteTheme')->name('site');
        Route::get('global', 'globalSetting')->name('global');
        Route::get('colors', 'colorsSetting')->name('colors');
        // Route::get('colors', 'colorsSetting')->name('colors');
        Route::get('fonts', 'fontSetting')->name('fonts');
        Route::get('dynamic-landing', 'dynamicLanding')->name('dynamic-landing');

        Route::get('status-update', 'statusUpdate')->name('status-update');

        Route::post('dynamic-landing-update', 'dynamicLandingUpdate')->name('dynamic-landing-update');
        Route::get('dynamic-landing-status-update', 'dynamicLandingStatusUpdate')->name('dynamic-landing-status-update');
        Route::post('dynamic-landing-delete/{id}', 'dynamicLandingDelete')->name('dynamic-landing-delete');
    });

    Route::group(['prefix' => 'page', 'as' => 'page.', 'controller' => PageController::class], function () {
        Route::get('settings', 'pageSetting')->name('setting');
        Route::post('setting-update', 'pageSettingUpdate')->name('setting.update');
    });

    Route::group(['prefix' => 'social', 'as' => 'social.', 'controller' => SocialController::class], function () {
        Route::post('store', 'store')->name('store');
        Route::post('update', 'update')->name('update');
        Route::post('delete', 'delete')->name('delete');
        Route::post('position-update', 'positionUpdate')->name('position.update');
    });


    //===============================  site Settings ==================================
    Route::group(['prefix' => 'settings', 'as' => 'settings.', 'controller' => SettingController::class], function () {
        Route::get('/', 'index')->name('index');
        Route::get('site', 'siteSetting')->name('site');
        Route::get('mail', 'mailSetting')->name('mail');
        Route::get('google-mail', 'googleMailSetting')->name('googleMail');
        Route::get('sendgrid', 'sendGridSetting')->name('sendGrid');
        Route::get('ses', 'sesSetting')->name('ses');
        Route::get('forex-api', 'forexApiSetting')->name('forex-api');
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

        Route::get('company', 'companySetting')->name('company');
        Route::get('currency', 'currencySetting')->name('currency');
        Route::get('site-maintenance', 'siteMaintenance')->name('site-maintenance');
        Route::get('transfers', 'transfers')->name('transfers');
        Route::get('gdpr', 'gdpr')->name('gdpr');
        Route::get('dev-mode', 'devMode')->name('devMode');
        Route::get('clear-cache', 'clearCache')->name('clearCache');
        Route::get('api-access', 'apiAccess')->name('apiAccess');
        Route::get('web-hook', 'webHook')->name('webHook');
        Route::get('end-to-end-encryption', 'endToEndEncryption')->name('endToEndEncryption');

        Route::get('slack', 'slackSetting')->name('slack');

        Route::get('platform-api', 'platformApiSetting')->name('platform-api');
        Route::get('misc', 'miscSetting')->name('misc');
        Route::get('copy_trading', 'copyTradingSetting')->name('copyTrading');

        Route::get('company/permissions', 'companyPermissions')->name('company.permissions');
        Route::get('customer/permissions', 'customerPermissions')->name('customer.permissions');

        Route::get('mt5-webterminal', 'mt5WebterminalSetting')->name('webterminal.mt5');
        Route::get('x9-webterminal', 'x9WebterminalSetting')->name('webterminal.x9');
        Route::post('mt5/db/test-connection', 'testDatabaseConnection')->name('testConnection');

    });

    Route::get('grpd-compliance', [SettingController::class, 'grpdCompliance'])->name('grpdCompliance');
    Route::get('changelog', [SettingController::class, 'changelog'])->name('changelog');
    Route::get('/feature-locked', [SettingController::class, 'featureLocked'])->name('feature.locked');


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
    Route::get('email-template/user', [EmailTemplateController::class, 'userTemplate'])->name('email-template.user');
    Route::get('email-template-edit/{id}', [EmailTemplateController::class, 'edit'])->name('email-template-edit');
    Route::post('email-template-update', [EmailTemplateController::class, 'update'])->name('email-template-update');

    Route::group(['prefix' => 'template', 'as' => 'template.'], function () {
        Route::group(['prefix' => 'sms', 'as' => 'sms.', 'controller' => SmsController::class], function () {
            Route::get('/', 'template')->name('index');
            Route::get('user', 'userTemplate')->name('user-template');
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
        Route::get('legal-links', 'legalLinks')->name('legal-links');
        Route::get('platform-links', 'platformLinks')->name('platform-links');

        Route::get('social', [SocialLinkController::class, 'index'])->name('social.index');
        Route::get('social/{id}', [SocialLinkController::class, 'edit'])->name('social.edit');
        Route::put('social/update', [SocialLinkController::class, 'update'])->name('social.update');
    });


    //===============================  Others ==================================
    Route::group(['controller' => AppController::class], function () {
        Route::get('subscribers', 'subscribers')->name('subscriber');
        Route::get('mail-send-subscriber', 'mailSendSubscriber')->name('mail.send.subscriber');
        Route::post('mail-send-subscriber-now', 'mailSendSubscriberNow')->name('mail.send.subscriber.now');
    });
    Route::group(['prefix' => 'support-ticket', 'as' => 'ticket.', 'controller' => TicketController::class], function () {
        Route::get('index/{id?}', 'index')->name('index');
        Route::get('status', 'ticketStatus')->name('ticketStatus');
        Route::get('priority', 'ticketPriority')->name('ticketPriority');
        Route::post('reply', 'reply')->name('reply');
        Route::get('show/{uuid}', 'show')->name('show');
        Route::get('close-now/{uuid}', 'closeNow')->name('close.now');

        Route::resource('statuses', TicketStatusController::class);
        Route::resource('priorities', TicketPriorityController::class);

    });


    //admin self manage
    Route::get('profile', [AppController::class, 'profile'])->name('profile');
    Route::post('profile-update', [AppController::class, 'profileUpdate'])->name('profile-update');

    Route::get('password-change', [AppController::class, 'passwordChange'])->name('password-change');
    Route::post('password-update', [AppController::class, 'passwordUpdate'])->name('password-update');

    Route::get('application-info', [AppController::class, 'applicationInfo'])->name('application-info');
    Route::get('clear-cache', [AppController::class, 'clearCache'])->name('clear-cache');


    Route::get('import-form', [ImportController::class, 'index'])->name('import-form');
    Route::post('import', [ImportController::class, 'import'])->name('import');

    Route::get('/reports', function () {
        return view('backend.reports.index');
    });

    Route::get('staff/2fa/pin', [StaffController::class, 'twoFaPin'])->name('staff.2fa.pin');

    Route::get('settings/country', [CountryController::class, 'index'])->name('country.all');

    Route::get('settings/platform-api/match-trader', function () {
        return view('backend.setting.platform_api.match_trader');
    })->name('platform_api.match_trader');

    Route::get('settings/platform-api/cTrader', function () {
        return view('backend.setting.platform_api.ctrader');
    })->name('platform_api.ctrader');

    Route::get('settings/platform-api/db-synchronization', function () {
        return view('backend.setting.platform_api.db-synchronization');
    })->name('platform_api.db-synchronization');

    Route::get('settings/platform-api/db-x9trader', function () {
        return view('backend.setting.platform_api.db-x9trader');
    })->name('platform_api.dbX9trader');

    Route::get('settings/platform-api/x9trader', function () {
        return view('backend.setting.platform_api.x9trader');
    })->name('platform_api.x9trader');  
    
    Route::resource('departments', DepartmentController::class)->only('index','create','store', 'edit', 'update', 'destroy');
    Route::resource('designations', DesignationController::class)->only('index','create','store', 'edit', 'update', 'destroy');

    Route::get('settings/report-issues', function () {
        return view('backend.system.report_issues');
    })->name('reportIssues');


});
