<?php

use App\Http\Controllers\Backend\RiskHubController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\IBController;
use App\Http\Controllers\Backend\AppController;
use App\Http\Controllers\Backend\KycController;
use App\Http\Controllers\Backend\SmsController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\BlogController;
use App\Http\Controllers\Backend\LinkController;
use App\Http\Controllers\Backend\NoteController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\BonusController;
use App\Http\Controllers\Backend\StaffController;
use App\Http\Controllers\Backend\ThemeController;
use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\Backend\ImportController;
use App\Http\Controllers\Backend\PluginController;
use App\Http\Controllers\Backend\ProfitController;
use App\Http\Controllers\Backend\SocialController;
use App\Http\Controllers\Backend\SymbolController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\LabelController;
use App\Http\Controllers\Backend\TicketController;
use App\Http\Controllers\Backend\CountryController;
use App\Http\Controllers\Backend\DepositController;
use App\Http\Controllers\Backend\GatewayController;
use App\Http\Controllers\Backend\Mt5DealController;
use App\Http\Controllers\Backend\RankingController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\AccountsController;
use App\Http\Controllers\Backend\IBSchemaController;
use App\Http\Controllers\Backend\LanguageController;
use App\Http\Controllers\Backend\PositionController;
use App\Http\Controllers\Backend\ReferralController;
use App\Http\Controllers\Backend\ScheduleController;
use App\Http\Controllers\Backend\SecurityController;
use App\Http\Controllers\Backend\WithdrawController;
use App\Http\Controllers\Backend\CustomCssController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\KYCLevelsController;
use App\Http\Controllers\Backend\SystemTagController;
use App\Http\Controllers\Backend\MultiIbLevelController;

use App\Http\Controllers\Backend\DepartmentController;
use App\Http\Controllers\Backend\MultiLevelController;
use App\Http\Controllers\Backend\NavigationController;
use App\Http\Controllers\Backend\RebateRuleController;
use App\Http\Controllers\Backend\DesignationController;
use App\Http\Controllers\Backend\ForexSchemaController;
use App\Http\Controllers\Backend\SymbolGroupController;
use App\Http\Controllers\Backend\TransactionController;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\Backend\TicketStatusController;
use App\Http\Controllers\Backend\CustomerGroupController;
use App\Http\Controllers\Backend\EmailTemplateController;
use App\Http\Controllers\Backend\LevelReferralController;
use App\Http\Controllers\Backend\PlatformGroupController;
use App\Http\Controllers\Backend\RiskProfileTagController;
use App\Http\Controllers\Backend\TicketPriorityController;
use App\Http\Controllers\Backend\ProfitDeductionController;
use App\Http\Controllers\Backend\BlackListCountryController;
use App\Http\Controllers\Backend\IslamicMultiLevelController;
use App\Http\Controllers\Backend\AdvertisementMaterialController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\Backend\IBGroupController;
use App\Http\Controllers\Backend\DocumentLinkController;
use App\Http\Controllers\Backend\PlatformLinkController;
use App\Http\Controllers\Backend\PlatformApiController;
use App\Http\Controllers\Backend\SocialLinkController;
use App\Http\Controllers\Backend\LeadController;
use App\Http\Controllers\Backend\LeadSourceController;
use App\Http\Controllers\Backend\LeadStageController;
use App\Http\Controllers\Backend\LeadPipelineController;
use App\Http\Controllers\Backend\DealController;
use App\Http\Controllers\Backend\DealNoteController;


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
//Route::group(['middleware' => [ '2fa']], function () {
Route::middleware(['2fa_admin', 'payment_access', 'set.session.lifetime:admin'])->group(function () {

    //Admin Dashboard
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/staff/dashboard', [DashboardController::class, 'staffDashboard'])->name('staff.dashboard');


    // Customer Management
    Route::resource('user', UserController::class)->only('index', 'edit', 'update', 'destroy');
    Route::group(['prefix' => 'user', 'as' => 'user.', 'controller' => UserController::class], function () {
        Route::get('active', 'activeUser')->name('active');
        Route::get('disabled', 'disabled')->name('disabled');
        Route::get('withBalance', 'withBalance')->name('with_balance');
        Route::get('withOutBalance', 'withOutBalance')->name('without_balance');
        Route::get('login/{id}', 'userLogin')->name('login');
        Route::post('status-update/{id}', 'statusUpdate')->name('status-update');
        Route::post('password-reset', 'resetPassword')->name('reset-password');
        Route::post('password-update/{id}', 'passwordUpdate')->name('password-update');
        Route::post('balance-update/{id}', 'balanceUpdate')->name('balance-update');
        Route::get('mail-send/all', 'mailSendAll')->name('mail-send.all');
        Route::post('mail-send', 'mailSend')->name('mail-send');
        Route::get('transaction/{id}', 'transaction')->name('transaction');
        Route::get('ib-bonus/{id}', 'ibBonus')->name('ib_bonus');
        Route::get('ib-info/{id}', 'ibInfo')->name('ib-info');
        Route::post('export/{type?}', 'export')->name('export');
        Route::get('create', 'createCustomer')->name('create');
        // Route::post('note/create/{id}', 'createNote')->name('note.ssadd');password-update
        // Route::post('note/create/{id}', 'createNote')->name('note.add');
        Route::post('store', 'store')->name('store');
        Route::post('kyc/{id}', 'kyc')->name('kyc');
    });

    Route::group(['prefix' => 'user/note', 'as' => 'user.note.', 'controller' => NoteController::class], function () {
        Route::get('data/{id}', 'index')->name('data');
        Route::post('create/{id}', 'create')->name('add');
        Route::put('edit/{id}', 'update')->name('edit');
        Route::delete('delete/{id}', 'destroy')->name('delete');
    });

    Route::resource('kyc-form', KycController::class);
    Route::group(['prefix' => 'kyc', 'as' => 'kyc.', 'controller' => KycController::class], function () {
        Route::get('editlevel2/{id}', 'editKycLevel2')->name('editKycLevel2');
        Route::post('updatelevel2kyc/{id}', 'updateLevel2Kyc')->name('updateLevel2Kyc');
        Route::get('pending', 'KycPending')->name('pending');
        Route::get('pending/level3', 'KycLevel3Pending')->name('level3.pending');
        Route::post('storelevel2', 'storeLevel2')->name('storeLevel2');
        Route::post('storelevel3', 'storeLevel3')->name('storeLevel3');
        Route::get('rejected', 'KycRejected')->name('rejected');
        Route::get('action/{id}', 'depositAction')->name('action');
        Route::get('level3/action/{id}', 'depositActionLevel3')->name('level3.action');
        Route::post('action-now', 'actionNow')->name('action.now');
        Route::post('level3-action-now', 'actionLevel3Now')->name('action.level3.now');
        Route::get('all', 'kycAll')->name('all');
        Route::post('export/{type?}', 'export')->name('export');
    });
//===============================  System Tag ==================================
    Route::resource('system-tag', SystemTagController::class);

    //===============================  System Tag ==================================
    Route::resource('multi-ib-level', MultiIbLevelController::class);

//    Route::get('multi-ib-level/{id}/edit', [MultiIbLevelController::class, 'edit'])->name('multi-ib-level.edit');
//    Route::put('multi-ib-level/{id}', [MultiIbLevelController::class, 'update'])->name('multi-ib-level.update');
//    Route::delete('multi-ib-level/{multi_ib_level}', [MultiIbLevelController::class, 'destroy'])->name('multi-ib-level.destroy');

    //===============================  IB Groups ==================================
    Route::resource('ib-group', IBGroupController::class);


//===============================  Risk Profile Tag ==================================
    Route::resource('risk-profile-tag', RiskProfileTagController::class);
    Route::group(['prefix' => 'risk-profile-tag', 'as' => 'risk-profile-tag.', 'controller' => RiskProfileTagController::class], function () {
        Route::post('tag/update/{id}', 'tagsUpdate')->name('tag.update');
        Route::post('tag/delete/{id}', 'tagDelete')->name('tag.delete');
    });
    Route::resource('kyclevels', KYCLevelsController::class);
    Route::group(['prefix' => 'kyc', 'as' => 'kyc.', 'controller' => KYCLevelsController::class], function () {
        Route::post('level/update/{id}', 'kycLevelUpdate')->name('level.update');
        Route::post('subLevel/update/{id}', 'kycSubLevelUpdate')->name('subLevel.update');
        //Route::post('tag/delete/{id}', 'tagDelete')->name('tag.delete');

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
        Route::post('multi/approve', 'approveMIbMember')->name('multi.approve');
        Route::post('multi/update', 'updateMIbMember')->name('multi.update');
        Route::post('reject', 'rejectIbMember')->name('reject');
        Route::post('save/form', 'saveForm')->name('save.form');
        Route::post('export/{type?}', 'export')->name('export');

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
        //            dd(route('admin.dashboard'));
        return redirect(route('admin.dashboard'));
    })->name('2fa.verify');

    Route::get('login/{id}', [StaffController::class, 'staffLogin'])->name('staff.login');
    Route::post('stop-impersonation', [StaffController::class, 'stopImpersonation'])->name('stop.impersonation');
    Route::post('staff/{staffId}/detach-user', [StaffController::class, 'detachUser'])->name('staff.detachUser');


    //===============================  Plans Management ==================================
    Route::resource('schedule', ScheduleController::class)->except('show', 'destroy', 'create');
    Route::resource('accountType', ForexSchemaController::class)->except('show', 'destroy');
    Route::get('manage-level', [ForexSchemaController::class, 'manageLevel'])->name('manageLevel');
    Route::get('multi-level/view/{id}', [ForexSchemaController::class, 'view'])->name('multi-level.view');
    Route::delete('accountType/{accountTypeId}', [ForexSchemaController::class, 'destroy'])->name('accountType.delete');
    Route::resource('ibAccountType', IBSchemaController::class)->except('show', 'destroy');
    Route::delete('ibAccountType/{ibAccountTypeId}', [IBSchemaController::class, 'destroy'])->name('ibAccountType.delete');
    Route::resource('blackListCountry', BlackListCountryController::class)->except('show');

    Route::group(['prefix' => 'forex', 'as' => 'forex.'], function () {
        Route::post('get/leverage', [AccountsController::class, 'getLeverage'])->name('get.leverage');
        Route::post('get/schema', [AccountsController::class, 'getSchema'])->name('get.schema');
        Route::post('update/account', [AccountsController::class, 'updateAccountInfo'])->name('update.account');
    });

    //===============================  Profit Deduction Management ==================================
    Route::get('profit/deduction', [ProfitDeductionController::class, 'index'])->name('profit.deduction.index');
    Route::post('profit/deduction/store', [ProfitDeductionController::class, 'store'])->name('profit.deduction.store');

    //===============================  Transactions ==================================
    Route::get('transactions/{id?}', [TransactionController::class, 'transactions'])->name('transactions');
    Route::post('transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
    Route::get('transactions/view/{id}', [TransactionController::class, 'view'])->name('transactions.view');
    Route::get('investments/{id?}', [AccountsController::class, 'investments'])->name('investments');
    Route::get('forex-accounts/{type?}/{id?}', [AccountsController::class, 'forexAccounts'])->name('forex-accounts');
    Route::post('forex-accounts/export/{type?}', [AccountsController::class, 'export'])->name('forex-accounts.export');
    Route::post('reset/credit/{id?}', [AccountsController::class, 'resetCredit'])->name('reset.credit');

    Route::post('forex-account-create', [AccountsController::class, 'forexAccountCreateNow'])->name('forex-account-create');
    Route::post('forex-account-map', [AccountsController::class, 'forexAccountMap'])->name('forex-account-map');
    Route::get('all-leverage', [AccountsController::class, 'allLeverage'])->name('all-leverage');
    Route::post('all-leverage/action', [AccountsController::class, 'handleAllLeverage'])->name('all-leverage.action');
    Route::get('pending-leverage', [AccountsController::class, 'pendingLeverage'])->name('pending-leverage');
    Route::post('pending-leverage/action', [AccountsController::class, 'handlePendingLeverage'])->name('pending-leverage.action');
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
            Route::delete('delete/{id}', 'destroy')->name('delete')->withoutMiddleware('XSS');
        });
        //=============================== end deposit Method ================================

        Route::get('manual-pending', 'pending')->name('manual.pending');
        Route::get('history', 'history')->name('history');
        Route::post('export',  'export')->name('export');
        Route::get('action/{id}', 'depositAction')->name('action');
        Route::post('action-now', 'actionNow')->name('action.now');

        Route::get('add', 'addDeposit')->name('add');
        Route::get('gateway/{code}', 'gateway')->name('gateway');
        Route::post('now', 'depositNow')->name('now');
        Route::get('get/user/accounts/{userId}', 'getUserAccounts')->name('get.user.accounts');
    });

    Route::group(['prefix' => 'withdraw', 'as' => 'withdraw.', 'controller' => WithdrawController::class], function () {
        //=============================== withdraw Method ================================

        Route::group(['prefix' => 'method', 'as' => 'method.'], function () {
            Route::get('list/{type}', 'methods')->name('list');
            Route::get('create/{type}', 'methodCreate')->name('create');
            Route::post('store', 'methodStore')->name('store')->withoutMiddleware('XSS');
            Route::get('edit/{type}', 'methodEdit')->name('edit');
            Route::post('update/{id}', 'methodUpdate')->name('update')->withoutMiddleware('XSS');
            Route::delete('delete/{id}', 'destroy')->name('delete')->withoutMiddleware('XSS');
        });

        //Schedule
        Route::get('schedule', 'schedule')->name('schedule');
        Route::post('schedule-update', 'scheduleUpdate')->name('schedule.update');
        Route::post('export',  'export')->name('export');
        Route::get('history', 'history')->name('history');
        Route::get('pending', 'pending')->name('pending');
        Route::post('pending/export', 'pendingExport')->name('pending.export');
        Route::get('action/{id}', 'withdrawAction')->name('action');
        Route::post('action-now', 'actionNow')->name('action.now');

        Route::get('add', 'addWithdraw')->name('add');
        Route::get('get/user/accounts/{userId}', 'getUserAccounts')->name('get.user.accounts');
        Route::get('details/{accountId}/{amount?}', 'details')->name('details');
        Route::post('now', 'withdrawNow')->name('now');
        Route::get('method/{id}', 'withdrawAccount')->name('account');
        Route::post('account/store', 'accountStore')->name('account.store');
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

        Route::get('site', 'siteTheme')->name('site');
        Route::get('global', 'globalSetting')->name('global');
        Route::get('colors', 'colorsSetting')->name('colors');
        Route::get('fonts', 'fontSetting')->name('fonts');
        Route::get('dynamic-landing', 'dynamicLanding')->name('dynamic-landing');

        Route::get('status-update', 'statusUpdate')->name('status-update');

        Route::post('dynamic-landing-update', 'dynamicLandingUpdate')->name('dynamic-landing-update');
        Route::get('dynamic-landing-status-update', 'dynamicLandingStatusUpdate')->name('dynamic-landing-status-update');
        Route::post('dynamic-landing-delete/{id}', 'dynamicLandingDelete')->name('dynamic-landing-delete');

        Route::get('popup', 'popup')->name('popup');
    });

    Route::group(['prefix' => 'page', 'as' => 'page.', 'controller' => PageController::class], function () {
        Route::get('settings', 'pageSetting')->name('setting');
        Route::post('setting-update', 'pageSettingUpdate')->name('setting.update');
    });

    Route::group(['prefix' => 'social', 'as' => 'social.', 'controller' => SocialController::class], function () {
        Route::get('/', 'index')->name('index');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::put('update', 'update')->name('update');
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

        Route::get('get-rate/{code}', [RateController::class, 'get_rate'])->name('currency.get-rate');

        Route::get('company', 'companySetting')->name('company');
        Route::get('currency', 'currencySetting')->name('currency');
        Route::get('site-maintenance', 'siteMaintenance')->name('site-maintenance');
        Route::get('transfers', 'transfers')->name('transfers');
        Route::get('gdpr', 'gdpr')->name('gdpr');
        Route::get('dev-mode', 'devMode')->name('devMode');
        Route::get('clear-cache', 'clearCache')->name('clearCache');
        Route::get('api-access', 'apiAccess')->name('apiAccess');
        Route::get('web-hook', 'webHook')->name('webHook');
        Route::get('documentation', 'documentation')->name('documentation');
        Route::get('end-to-end-encryption', 'endToEndEncryption')->name('endToEndEncryption');

        Route::get('slack', 'slackSetting')->name('slack');

        Route::get('platform-api', 'platformApiSetting')->name('platform-api');
        Route::get('misc', 'miscSetting')->name('misc');
        Route::get('copy_trading', 'copyTradingSetting')->name('copyTrading');

        Route::get('company/permissions', 'companyPermissions')->name('company.permissions');
        Route::get('customer/permissions', 'customerPermissions')->name('customer.permissions');
        Route::get('customer/kycpermissions', 'kycPermissions')->name('customer.kycpermissions');

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
    Route::get('email-template/create', [EmailTemplateController::class, 'create'])->name('email-template-create');
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
    Route::group(['prefix' => 'links', 'as' => 'links.'], function () {
        Route::get('document', [DocumentLinkController::class, 'index'])->name('document.index');
        Route::post('document/store', [DocumentLinkController::class, 'store'])->name('document.store');
        Route::get('document/{id}', [DocumentLinkController::class, 'edit'])->name('document.edit');
        Route::put('document/update', [DocumentLinkController::class, 'update'])->name('document.update');
        Route::delete('document/{id}', [DocumentLinkController::class, 'destroy'])->name('document.destroy');

        Route::get('platform', [PlatformLinkController::class, 'index'])->name('platform.index');
        Route::post('platform/store', [PlatformLinkController::class, 'store'])->name('platform.store');
        Route::get('platform/{id}', [PlatformLinkController::class, 'edit'])->name('platform.edit');
        Route::put('platform/update', [PlatformLinkController::class, 'update'])->name('platform.update');
        Route::delete('platform/{id}', [PlatformLinkController::class, 'destroy'])->name('platform.destroy');

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
        Route::get('new-ticket', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('index/{id?}', 'index')->name('index');
        Route::get('status', 'ticketStatus')->name('ticketStatus');
        Route::get('priority', 'ticketPriority')->name('ticketPriority');
        Route::post('reply', 'reply')->name('reply');
        Route::get('show/{uuid}', 'show')->name('show');
        Route::patch('{ticket}/update', 'update')->name('update');

        // routes/web.php
        Route::get('assign/{ticket}', 'showAssignModal')->name('showAssignModal');
        Route::post('{ticket}/assign', 'assignTicket')->name('assign');

        Route::patch('{ticket}/close', 'close')->name('close');
        Route::patch('{ticket}/reopen', 'reopen')->name('reopen');
        Route::patch('{ticket}/archive', 'archive')->name('archive');
        Route::patch('{ticket}/resolve', 'resolve')->name('resolve');

        Route::resource('category', CategoryController::class);
        Route::resource('label', LabelController::class);
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


    Route::get('/ib-resources', function () {
        return view('backend.ib.resources.index');
    });

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

    // Bonus Module
    Route::resource('bonus', BonusController::class);
    Route::post('bonus-add/{user}', [BonusController::class, 'addBonusByAdmin'])->name('bonus.add');

    Route::get('/symbol-groups', function () {
        return view('backend.symbol_groups.metatrader5');
    });


    Route::get('staff/2fa/pin', [StaffController::class, 'twoFaPin'])->name('staff.2fa.pin');

    Route::get('settings/country', [CountryController::class, 'index'])->name('country.all');

    Route::get('settings/platform-api/cTrader', [PlatformApiController::class, 'cTrader'])
    ->name('platform_api.ctrader');

    Route::get('settings/platform-api/db-synchronization', [PlatformApiController::class, 'dbSynchronization'])->name('platform_api.db-synchronization');

    Route::get('settings/platform-api/db-x9trader', function () {
        return view('backend.setting.platform_api.db-x9trader');
    })->name('platform_api.dbX9trader');

    Route::get('settings/platform-api/x9trader', [PlatformApiController::class, 'x9Trader'])->name('platform_api.x9trader');

    Route::get('announcements', function () {
        return view('backend.announcements.index');
    })->name('announcements');

    Route::resource('customer-groups', CustomerGroupController::class)->only('index', 'store', 'create', 'edit', 'update', 'destroy');
    Route::resource('departments', DepartmentController::class)->only('index', 'create', 'store', 'edit', 'update', 'destroy');
    Route::resource('designations', DesignationController::class)->only('index', 'create', 'store', 'edit', 'update', 'destroy');
    Route::resource('swap-multi-level', MultiLevelController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('symbol-groups', SymbolGroupController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('symbols', SymbolController::class)->only(['index', 'create', 'edit', 'update', 'destroy']);
    Route::post('symbols/updateStatus', [SymbolController::class, 'updateStatus'])->name('symbols.updateStatus');
    Route::post('symbols/enableAll', [SymbolController::class, 'enableAll'])->name('symbols.enableAll');
    Route::get('symbols/export', [SymbolController::class, 'export'])->name('symbols.export');

    Route::resource('rebate-rules', RebateRuleController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::post('rebate-rules/update-status', [RebateRuleController::class, 'updateStatus'])->name('rebateRules.updateStatus');


    Route::get('get-deals/{login}', [Mt5DealController::class, 'getDeals'])->name('getDeals');
    Route::get('theme/banners', [BannerController::class, 'index'])->name('banners');
    Route::put('banner/{id}', [BannerController::class, 'update'])->name('banner.update');

    Route::post('/positions/active', [PositionController::class, 'getGroupPosition'])->name('positions.group');
    Route::post('/positions/days', [PositionController::class, 'positionByDays'])->name('positions.days');
    Route::post('/positions/account', [PositionController::class, 'getPositionByAccount'])->name('positions.account');
    Route::post('/positions/group', [PositionController::class, 'getGroupNetPosition'])->name('netPositions.group');


    Route::controller(RiskHubController::class)->group(function () {
        Route::get('active-positions', 'activePositions')->name('activePositions');
        Route::get('net-positions-accounts', 'netPositionsAccounts')->name('netPositionsAccounts');
        Route::get('net-positions-groups', 'netPositionsGroups')->name('netPositionsGroups');
        Route::get('older-positions-days', 'olderPositionsDays')->name('olderPositionsDays');
    });

    Route::get('platform/groups', [PlatformGroupController::class, 'index'])->name('platformGroups');
    Route::post('/groups/assign-risk-book', [PlatformGroupController::class, 'assignRiskBook'])->name('groups.assignRiskBook');
    Route::post('platform/groups/store', [PlatformGroupController::class, 'store'])->name('groups.store');
    Route::get('platform/groups/manual', [PlatformGroupController::class, 'manualGroupListing'])->name('manual.platformGroups');
    Route::post('platform/groups/store/manually', [PlatformGroupController::class, 'storeManualGroup'])->name('groups.storeManually');
    Route::get('platform/groups/{id}/edit', [PlatformGroupController::class, 'editManualGroup'])->name('groups.editManually');
    Route::put('platform/groups/{id}', [PlatformGroupController::class, 'updateManualGroup'])->name('groups.updateManually');
    Route::put('platform/groups/{id}', [PlatformGroupController::class, 'updateManualGroup'])->name('groups.updateManually');
    Route::delete('platform/groups/{id}', [PlatformGroupController::class, 'deleteManualGroup'])->name('group.delete');
    Route::get('platform/risk-book', [PlatformGroupController::class, 'getRiskBook'])->name('platform.riskBook');
    Route::post('risk-book/{id}/update', [PlatformGroupController::class, 'updateRiskBook'])->name('riskBook.update');
    Route::get('risk-books/{id}', [PlatformGroupController::class, 'riskBookShow'])->name('riskBook.show');


    Route::group(['prefix' => 'lead', 'as' => 'lead.', 'controller' => LeadController::class], function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('show/{id}', 'show')->name('show');
        Route::get('{id}/edit', 'edit')->name('edit');
        Route::put('update/{id}', 'update')->name('update');
        Route::delete('delete/{id}', 'destroy')->name('destroy');
        Route::post('stage-update/{id}', 'stageUpdate')->name('stageUpdate');
        Route::get('get-lead/{id}', 'getLead')->name('getLead');
        Route::get('create-client/{id}', 'createClient')->name('createClient');

        Route::post('store/client', [UserController::class, 'leadAsClient'])->name('storeAsClient');

        Route::resource('source', LeadSourceController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('stage', LeadStageController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::get('stage-status-update/{statusId}', [LeadStageController::class, 'statusUpdate'])->name('stage.statusUpdate');
        Route::resource('pipeline', LeadPipelineController::class);
        Route::get('pipeline-status-update/{statusId}', [LeadPipelineController::class, 'statusUpdate'])->name('pipeline.statusUpdate');
    });

    Route::group(['prefix' => 'deal', 'as' => 'deal.', 'controller' => DealController::class], function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('show/{id}', 'show')->name('show');
        Route::get('{id}/edit', 'edit')->name('edit');
        Route::put('update/{id}', 'update')->name('update');
        Route::delete('delete/{id}', 'destroy')->name('destroy');
        Route::get('get-stage/{id}', 'getStages')->name('get-stage');
        Route::post('stage-update/{id}', 'stageUpdate')->name('stageUpdate');

        Route::resource('note', DealNoteController::class);
    });


    Route::get('fraud-protection', function () {
        return view('backend.fraud_protection.index');
    })->name('fraudProtection');


//    Route::get('changelog', [AppController::class, 'changeLog'])->name('changelog');

    Route::get('deposit/misc-setting', function () {
        return view('backend.setting.payment.deposit.misc');
    })->name('deposit.miscSetting');

    Route::get('withdraw/misc-setting', function () {
        return view('backend.setting.payment.withdraw.misc');
    })->name('withdraw.miscSetting');

    Route::get('settings/report-issues', [AppController::class, 'reportIssue'])->name('reportIssues');

    Route::get('settings/route', function () {
        return view('backend.setting.customization.routes');
    })->name('routeSetting');

    Route::get('settings/dynamic-content', function () {
        return view('backend.setting.customization.dynamic_content');
    })->name('dynamicContent');

    Route::get('leads', function () {
        $tags = App\Models\RiskProfileTag::where('status', true)->get();
        return view('backend.lead.index', compact('tags'));
    })->name('customerLead');

});
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->withoutMiddleware('isDemo');;
