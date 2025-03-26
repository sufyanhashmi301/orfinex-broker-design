<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Enums\TraderType;
use App\Traits\NotifyTrait;
use App\Models\AccountTrial;
use App\Enums\InvestmentStatus;
use Illuminate\Console\Command;
use App\Services\ForexApiService;
use App\Models\AccountTypeInvestment;
use App\Services\AccountActivityService;
use App\Services\AccountTypeInvestmentPaymentService;
use App\Services\MatchTraderApiService;

class TrialAccountsActiveExpire extends Command
{
    use NotifyTrait;

    protected $account_payment;
    protected $forexApiService;
    protected $matchTraderApiService;

    public function __construct(AccountTypeInvestmentPaymentService $account_payment, ForexApiService $forexApiService, MatchTraderApiService $matchTraderApiService) {
        parent::__construct();
        $this->account_payment = $account_payment;
        $this->forexApiService = $forexApiService;
        $this->matchTraderApiService = $matchTraderApiService;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trial-accounts:active-or-expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will run every 2 minutes to active any pending trial accounts or expire trial accounts.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $trial_accounts = AccountTypeInvestment::where('is_trial', 1)->get();

        foreach($trial_accounts as $account) {
            // Active if pending and reset expiry date
            if($account->status == InvestmentStatus::PENDING) {
                $account_approved = $this->account_payment->investmentActive($account->id);

                if(!$account_approved) {
                    return false;
                }

                if($account_approved->status == InvestmentStatus::ACTIVE) {
                    AccountActivityService::log($account_approved, 'Trial Active');
                    AccountTrial::where('account_type_investment_id', $account->id)->first()->update(['trial_expiry_at' => Carbon::now()->addDays(setting('auto_expire_expiry_days') + 1)]);
                } 
            }

            // Expire if active and date is arrived
            if($account->status == InvestmentStatus::ACTIVE) {
                $expiry = Carbon::parse($account->accountTrial->trial_expiry_at);
                if($expiry->lessThanOrEqualTo(Carbon::today())) {

                    // Deduct all the available balance from trial account
                    if($account->trader_type == TraderType::MT5) {
                        $data = [
                            'login' => $account->login,
                            'Amount' => $account->accountTypeInvestmentStat->balance,
                            'type' => 0,
                            'TransactionComments' => 'Trial Account Expired'
                        ];
                    
                        $response = $this->forexApiService->balanceOperation($data);
                    } elseif($account->trader_type == TraderType::MT) {
                        $deduct_balance_data = [
                        "systemUuid" => $account->getAccountTypeSnapshotData()['system_uuid'],
                        "login" => $account->login,
                        "amount" => $account->accountTypeInvestmentStat->balance,
                        "comment" => "Trial Account Expired"
                        ];
                    
                        $deduct_balance_response = $this->matchTraderApiService->deductBalance($deduct_balance_data);

                        if(!$deduct_balance_response) {
                            return false;
                        }
                    }

                    $account->update([
                        'phase_ended_at' => Carbon::now(),
                        'status' => InvestmentStatus::EXPIRED
                    ]);

                    // Do the email
                    $shortcodes = [
                        '[[full_name]]' => $account->user->first_name . ' ' . $account->user->last_name,
                        '[[site_title]]' => setting('site_title', 'global'),
                        '[[package_name]]' => $account->getAccountTypeSnapshotData()['title']
                    ];
                    $this->mailNotify($account->user->email, 'trial_expired', $shortcodes);

                    $account->accountTypeInvestmentStat->update(['balance' => 0, 'current_equity' => 0]);
                    AccountActivityService::log($account, 'Trial Expired');
                }
            }

        }

        return Command::SUCCESS;
    }
}
