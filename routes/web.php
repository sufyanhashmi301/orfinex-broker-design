<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\SumsubController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\Match2PayController;
use App\Http\Controllers\AccountBuyController;
use App\Http\Controllers\AccountTrialController;
use App\Http\Controllers\Frontend\IpnController;
use App\Http\Controllers\TradingStatsController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\UserAffiliateController;
use App\Http\Controllers\Backend\TwilioController;
use App\Http\Controllers\Frontend\StatusController;
use App\Http\Controllers\Frontend\TicketController;
use App\Http\Controllers\UserCertificateController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Frontend\DepositController;
use App\Http\Controllers\Frontend\GatewayController;
use App\Http\Controllers\Frontend\SettingController;
use App\Http\Controllers\UserVerificationController;
use App\Http\Controllers\Frontend\ContractController;
use App\Http\Controllers\Frontend\WithdrawController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Backend\LeaderboardController;
use App\Http\Controllers\AccountTypeInvestmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::controller(TwilioController::class)->group(function () {
    Route::post('/outbound.xml', 'outboundXml')->name('outbound-xml');
});

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/verify-email')->name('verification.notice');
Route::post('subscriber', [HomeController::class, 'subscribeNow'])->name('subscriber');

Route::get('user/verification/automatic-kyc/update', [KycController::class, 'updateAutomaticKyc'])->name('user.verification.automatic_kyc.update');

// M2Pay
Route::post('/m2p/deposit/crypto-agent', [Match2PayController::class, 'testCryptoDeposit']);

//User Part
Route::group(['middleware' => ['auth', '2fa', 'isActive', 'verified'], 'prefix' => 'user', 'as' => 'user.'], function () {
    //dashboard
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    //user notify
    Route::get('notify', [UserController::class, 'notifyUser'])->name('notify');
    Route::get('notification/all', [UserController::class, 'allNotification'])->name('notification.all');
    Route::get('latest-notification', [UserController::class, 'latestNotification'])->name('latest-notification');
    Route::get('notification-read/{id}', [UserController::class, 'readNotification'])->name('read-notification');

    //change Password
    Route::get('/change-password', [UserController::class, 'changePassword'])->name('change.password');
    Route::post('/password-store', [UserController::class, 'newPassword'])->name('new.password');    

    // ======== Optimizations ========

    // Account Buy
    Route::get('account/buy', [AccountBuyController::class, 'index'])->name('account.buy');
    Route::get('account/buy/{id}', [AccountBuyController::class, 'show'])->name('account.show');
    Route::post('account/trial/{id}', [AccountTrialController::class, 'freeTrial'])->name('account.free_trial');

    // Accounts
    Route::post('investment/', [AccountTypeInvestmentController::class, 'store'])->name('investment.store'); // Account Create
    Route::get('accounts/', [AccountTypeInvestmentController::class, 'index'])->name('investments.index'); // Account Shown
    Route::get('account/trading-stats/{account_id}', [TradingStatsController::class, 'userTradingStats'])->name('investment.trading-stats'); // Trading Stats
    Route::get('/account-stats', [AccountTypeInvestmentController::class, 'ajaxAccountStats'])->name('account_stats.login');

    // Affiliate Module
    Route::get('affiliate-area', [UserAffiliateController::class, 'index'])->name('affiliate-area.index');

    // Billing (User paid transactions)
    Route::get('billing', [BillingController::class, 'index'])->name('billing.index');
    Route::get('/billing/invoice/{transaction_id}', [BillingController::class, 'generateInvoice'])->name('billing.generateInvoice');

    // Certificates
    Route::get('/certificates', [UserCertificateController::class, 'index'])->name('certificates.index');

    // Contracts
    Route::get('contracts', [ContractController::class, 'index'])->name('contracts');
    Route::get('contract/{id}', [ContractController::class, 'show'])->name('contract.show');
    Route::post('contract/store', [ContractController::class, 'storeContract'])->name('contract.store');

    // Contract Agreement
    Route::get('agreements', function () {
        $legal_links = Setting::where('name', 'LIKE', '%legal_%')->where('name', 'LIKE', '%_show%')->get();
        return view('frontend::user.setting.agreements.index')->with('legal_links', $legal_links);
    })->name('agreements');

    // Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'userIndex'])->name('leaderboard');

    // Coupon Code
    Route::post('verify-coupon', [InvoiceController::class, 'verifyCoupon'])->name('verify_coupon');

    // KYC
    Route::get('/verification', [UserVerificationController::class, 'index'])->name('verification.index');
    Route::get('/verification/manual-kyc', [KycController::class, 'manualKyc'])->name('verification.manual_kyc');
    Route::get('/verification/manual-kyc/data/{option_name}', [KycController::class, 'manualKycData'])->name('verification.manual_kyc_data');
    Route::post('/verification/manual-kyc', [KycController::class, 'updateManualKyc'])->name('verification.manual_kyc.update');
    Route::get('/verification/automatic-kyc', [KycController::class, 'automaticKyc'])->name('verification.automatic_kyc');

    // ======== Optimizations ========

    // Deposit
    Route::group(['prefix' => 'deposit', 'as' => 'deposit.'], function () {
        Route::get('{id?}', [DepositController::class, 'deposit'])->name('amount');
        Route::get('gateway/{code}', [GatewayController::class, 'gateway'])->name('gateway');
        Route::post('now', [DepositController::class, 'depositNow'])->name('now');
        // Route::post('demo/now', [DepositController::class, 'depositDemoNow'])->name('demo.now');
        Route::get('log', [DepositController::class, 'depositLog'])->name('log');
    });

    //withdraw
    Route::group(['prefix' => 'withdraw', 'as' => 'withdraw.', 'controller' => WithdrawController::class], function () {
        //withdraw methods
        Route::resource('account', WithdrawController::class)->except('show');
        //user withdraw
        Route::get('/step-1', 'step1Index')->name('step1');
        Route::get('/payout-request', 'payoutRequest')->name('payout_request');


        Route::get('/step-2', 'withdraw')->name('step2');

        Route::post('now', 'withdrawNow')->name('now');
        
        // Witjdraw Ajax
        Route::get('details/{accountId}/{amount?}', 'details')->name('details');

        // success
        Route::get('success', 'success')->name('success');
        
        Route::get('method/{id}', 'withdrawMethod')->name('method');
        Route::get('log', 'withdrawLog')->name('log');
    });

    //email check
    Route::get('exist/{email}', [UserController::class, 'userExist'])->name('exist');

    //support ticket
    Route::group(['prefix' => 'support-ticket', 'as' => 'ticket.', 'controller' => TicketController::class], function () {
        Route::get('index', 'index')->name('index');
        Route::get('new', 'new')->name('new');
        Route::post('new-store', 'store')->name('new-store');
        Route::post('reply', 'reply')->name('reply');
        Route::get('show/{uuid}', 'show')->name('show');
        Route::get('close-now/{uuid}', 'closeNow')->name('close.now');
    });

    //settings
    Route::group(['prefix' => 'settings', 'as' => 'setting.', 'controller' => SettingController::class], function () {
        Route::get('/profile', 'profile')->name('profile');
        Route::get('/security', 'security')->name('security');
        Route::get('/', 'settings')->name('show');
        Route::get('2fa', 'twoFa')->name('2fa');
        Route::post('action-2fa', 'actionTwoFa')->name('action-2fa');
        Route::post('profile-update', 'profileUpdate')->name('profile-update');
        Route::post('info-update', 'infoUpdate')->name('info-update');

        Route::post('/2fa/verify', function (\Illuminate\Support\Facades\Request $request) {
            return redirect(route('user.dashboard'));
        })->name('2fa.verify');

        Route::get('/communication', function () {
            return view('frontend::user.setting.communication.index');
        })->name('communication');

    });


});

//translate
Route::get('language-update', [HomeController::class, 'languageUpdate'])->name('language-update');

//Gateway Manage
Route::get('gateway-list', [GatewayController::class, 'gatewayList'])->name('gateway.list')->middleware('XSS', 'translate', 'auth');

//Gateway status
Route::group(['controller' => StatusController::class, 'prefix' => 'status', 'as' => 'status.'], function () {
    Route::match(['get', 'post'], '/success', 'success')->name('success');
    Route::match(['get', 'post'], '/cancel', 'cancel')->name('cancel');
    Route::match(['get', 'post'], '/pending', 'pending')->name('pending');
    Route::match(['get', 'post'], '/pending', 'pending')->name('pending');
});

//Instant payment notification
Route::group(['prefix' => 'ipn', 'as' => 'ipn.', 'controller' => IpnController::class], function () {
    Route::post('coinpayments', 'coinpaymentsIpn')->name('coinpayments');
    Route::post('nowpayments', 'nowpaymentsIpn')->name('nowpayments');
    Route::post('bridgerpay', 'bridgerpayIpn')->name('bridgerpay');
    Route::post('match2pay', 'match2payIpn')->name('match2pay');
    Route::post('cryptomus', 'cryptomusIpn')->name('cryptomus');
    Route::get('paypal', 'paypalIpn')->name('paypal');
    Route::post('mollie', 'mollieIpn')->name('mollie');
    Route::any('perfectmoney', 'perfectMoneyIpn')->name('perfectMoney');
    Route::get('paystack', 'paystackIpn')->name('paystack');
    Route::get('flutterwave', 'flutterwaveIpn')->name('flutterwave');
    Route::post('coingate', 'coingateIpn')->name('coingate');
    Route::get('monnify', 'monnifyIpn')->name('monnify');
    Route::get('non-hosted-securionpay', 'nonHostedSecurionpayIpn')->name('non-hosted.securionpay')->middleware(['auth', 'XSS']);
    Route::post('coinremitter', 'coinremitterIpn')->name('coinremitter');
    Route::post('btcpay', 'btcpayIpn')->name('btcpay');
    Route::post('binance', 'binanceIpn')->name('binance');
    Route::get('blockchain', 'blockchainIpn')->name('blockchain');
    Route::get('instamojo', 'instamojoIpn')->name('instamojo');
    Route::post('paytm', 'paytmIpn')->name('paytm');
    Route::post('razorpay', 'razorpayIpn')->name('razorpay');
    Route::post('twocheckout', 'twocheckoutIpn')->name('twocheckout');
});

//site others
Route::get('theme-mode', [HomeController::class, 'themeMode'])->name('mode-theme');

//without auth
Route::get('notification-tune', [AppController::class, 'notificationTune'])->name('notification-tune');

Route::get('user/deposit-methods', function () {
    return view('frontend::deposit.deposit-methods');
})->name('user.deposit-methods');

Route::post('/telegram/webhook', [TelegramController::class, 'webhook']);

Route::get('user/utilities', function () {
    return view('frontend::utilities.index');
})->name('user.utilities');


Route::get('user/webterminal', function () {
    return view('frontend::webterminal.index');
})->name('webterminal');

Route::get('signature', function () {
    return view('frontend::contracts.signature');
})->name('signature');





