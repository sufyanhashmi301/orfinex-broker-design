<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\CronJobController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\DepositController;
use App\Http\Controllers\Frontend\ForexAccountController;
use App\Http\Controllers\Frontend\GatewayController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\InvestController;
use App\Http\Controllers\Frontend\IpnController;
use App\Http\Controllers\Frontend\KycController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\ReferralController;
use App\Http\Controllers\Frontend\ForexSchemaController;
use App\Http\Controllers\Frontend\SendMoneyController;
use App\Http\Controllers\Frontend\SettingController;
use App\Http\Controllers\Frontend\StatusController;
use App\Http\Controllers\Frontend\TicketController;
use App\Http\Controllers\Frontend\TransactionController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\Frontend\WithdrawController;
use App\Http\Controllers\Frontend\IBController;
use App\Http\Controllers\Frontend\TransferController;
use App\Http\Controllers\Frontend\OffersController;
use Illuminate\Support\Facades\Route;

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
Route::get('/', [HomeController::class, 'home'])->name('home');
Route::post('subscriber', [HomeController::class, 'subscribeNow'])->name('subscriber');

//Static Page
Route::get('/{page}', PageController::class)->name('page')->where('page', 'schema|how-it-works|about-us|faq|rankings|blog|contact|privacy-policy|terms-and-conditions');

//Dynamic Page
Route::get('page/{section}', [PageController::class, 'getPage'])->name('dynamic.page');

Route::get('blog/{id}', [PageController::class, 'blogDetails'])->name('blog-details');
Route::post('mail-send', [PageController::class, 'mailSend'])->name('mail-send');

//User Part
Route::group(['middleware' => ['auth', '2fa', 'isActive', setting('email_verification', 'permission') ? 'verified' : 'web'], 'prefix' => 'user', 'as' => 'user.'], function () {
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

    //kyc apply
    Route::get('kyc', [KycController::class, 'kyc'])->name('kyc');
    Route::get('kyc/basic', [KycController::class, 'basicKyc'])->name('kyc.basic');
    Route::get('kyc/{id}', [KycController::class, 'kycData'])->name('kyc.data');
    Route::post('kyc-submit', [KycController::class, 'submit'])->name('kyc.submit');

    Route::get('schemas', [ForexSchemaController::class, 'index'])->name('schema');
    Route::get('schema-preview/{id}', [ForexSchemaController::class, 'schemaPreview'])->name('schema.preview');

    //Forex accounts
    Route::post('forex-account-create-now', [ForexAccountController::class, 'forexAccountCreateNow'])->name('forex-account-create-now');
    Route::get('forex-account-logs', [ForexAccountController::class, 'forexAccountLogs'])->name('forex-account-logs');
    Route::get('invest-cancel/{id}', [ForexAccountController::class, 'investCancel'])->name('invest-cancel');
    Route::group(['prefix' => 'forex', 'as' => 'forex.'], function () {
        Route::post('get/leverage', [ForexAccountController::class, 'getLeverage'])->name('get.leverage');
        Route::post('update/account', [ForexAccountController::class, 'updateAccountInfo'])->name('update.account');

        Route::get('log', [ForexAccountController::class, 'depositLog'])->name('log');
    });
    //invest accounts
    Route::post('invest-now', [InvestController::class, 'investNow'])->name('invest-now');
    Route::get('invest-logs', [InvestController::class, 'investLogs'])->name('invest-logs');
    Route::get('invest-cancel/{id}', [InvestController::class, 'investCancel'])->name('invest-cancel');
    Route::get('transactions', [TransactionController::class, 'transactions'])->name('transactions');

    // Deposit
    Route::group(['prefix' => 'deposit', 'as' => 'deposit.'], function () {
        Route::get('', [DepositController::class, 'deposit'])->name('amount');
        Route::get('gateway/{code}', [GatewayController::class, 'gateway'])->name('gateway');
        Route::post('now', [DepositController::class, 'depositNow'])->name('now');
        Route::get('log', [DepositController::class, 'depositLog'])->name('log');
    });
    //Send Money
    Route::group(['prefix' => 'send-money', 'as' => 'send-money.', 'controller' => SendMoneyController::class], function () {
        Route::get('/', 'sendMoney')->name('view');
        Route::post('now', 'sendMoneyNow')->name('now');
        Route::get('/internal', 'sendMoneyInternal')->name('internal-view');
        Route::post('internal-now', 'sendMoneyInternalNow')->name('internal-now');
        Route::get('log', 'sendMoneyLog')->name('log');

    });

    //wallet exchange
    Route::get('wallet-exchange', [UserController::class, 'walletExchange'])->name('wallet-exchange');
    Route::post('wallet-exchange-now', [UserController::class, 'walletExchangeNow'])->name('wallet-exchange-now');

    //withdraw
    Route::group(['middleware' => 'KYC', 'prefix' => 'withdraw', 'as' => 'withdraw.', 'controller' => WithdrawController::class], function () {
        //withdraw methods
        Route::resource('account', WithdrawController::class)->except('show');
        //user withdraw
        Route::get('/', 'withdraw')->name('view');
        Route::get('details/{accountId}/{amount?}', 'details')->name('details');
        Route::get('method/{id}', 'withdrawMethod')->name('method');
        Route::post('now', 'withdrawNow')->name('now');
        Route::get('log', 'withdrawLog')->name('log');

    });
    //email check
    Route::get('exist/{email}', [UserController::class, 'userExist'])->name('exist');
    Route::get('exist/account/{account}', [ForexAccountController::class, 'userAccountExist'])->name('account.exist');
    //support ticket
    Route::group(['prefix' => 'support-ticket', 'as' => 'ticket.', 'controller' => TicketController::class], function () {
        Route::get('index', 'index')->name('index');
        Route::get('new', 'new')->name('new');
        Route::post('new-store', 'store')->name('new-store');
        Route::post('reply', 'reply')->name('reply');
        Route::get('show/{uuid}', 'show')->name('show');
        Route::get('close-now/{uuid}', 'closeNow')->name('close.now');
    });

    Route::get('referral', [ReferralController::class, 'referral'])->name('referral');
    Route::get('referral/advertisement/material', [ReferralController::class, 'advertisementMaterial'])->name('referral.advertisement.material');
    Route::get('download/image/{filename}', [ReferralController::class, 'download'])->where('filename', '.*')->name('image.download');

    Route::get('referral/network', [ReferralController::class, 'network'])->name('referral.network');
    Route::get('referral/reports', [ReferralController::class, 'reports'])->name('referral.reports');
    Route::get('ranking-badge', [UserController::class, 'rankingBadge'])->name('ranking-badge');

//    Route::get('referral/advertisement-material', function () {
//        return view('frontend::referral.index');
//    })->name('referral.advertisement-material');

    //settings
    Route::group(['prefix' => 'settings', 'as' => 'setting.', 'controller' => SettingController::class], function () {
        Route::get('/profile', 'profile')->name('profile');
        Route::get('/security', 'security')->name('security');
        Route::get('/', 'settings')->name('show');
        Route::get('2fa', 'twoFa')->name('2fa');
        Route::post('action-2fa', 'actionTwoFa')->name('action-2fa');
        Route::post('profile-update', 'profileUpdate')->name('profile-update');

        Route::post('/2fa/verify', function () {
            return redirect(route('user.dashboard'));
        })->name('2fa.verify');
    });

});

//translate
Route::get('language-update', [HomeController::class, 'languageUpdate'])->name('language-update');

//Gateway Manage
Route::get('gateway-list', [GatewayController::class, 'gatewayList'])->name('gateway.list')->middleware('XSS','translate','auth');

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
Route::get('schema-select/{id}', [ForexSchemaController::class, 'schemaSelect'])->name('user.schema.select');
Route::get('notification-tune', [AppController::class, 'notificationTune'])->name('notification-tune');

//site cron job
Route::get('cron-job/investment', [CronJobController::class, 'investmentCronJob'])->name('cron-job.investment');
Route::get('cron-job/profit/clear', [CronJobController::class, 'profitClearCronJob'])->name('cron-job.profit.clear');
Route::get('cron-job/referral', [CronJobController::class, 'referralCronJob'])->name('cron-job.referral');
Route::get('cron-job/user-ranking', [CronJobController::class, 'userRanking'])->name('cron-job.user-ranking');


Route::get('user/ib-program', [IBController::class, 'index'])->name('user.ib-program');
Route::post('/ib/transfer/balance', [IBController::class, 'ibTransferBalance'])->name('ib.transfer.balance');
Route::post('ib-program/store', [IBController::class, 'store'])->name('user.ib-program.store');


// Route::post('/ib/transfer/balance', 'IBController@ibTransferBalance')->name('user.ib.transfer.balance');

//Route::group(['middleware' => ['IB']], function() {
//    Route::get('user/ib-program/create', [IBController::class, 'create'])->name('user.ib-program.create');
//    Route::post('user/ib-program/store', [IBController::class, 'store'])->name('user.ib-program.store');
//
//});

Route::get('user/transfer', [TransferController::class, 'index'])->name('user.transfer');

Route::get('user/offers', [OffersController::class, 'index'])->name('user.offers');



Route::get('user/agreements', function () {
    return view('frontend.default.user.setting.agreements.index');
})->name('user.agreements');

Route::get('user/margin-account', function () {
    return view('frontend.default.user.setting.margin.index');
})->name('user.margin-account');


