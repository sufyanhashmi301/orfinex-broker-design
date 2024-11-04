<?php

namespace App\Services;

use App\Models\ForexSchemaInvestment;
use App\Enums\InterestPeriod;
use App\Enums\SchemeTermTypes;
use App\Enums\InvestmentStatus;

use App\Models\User;

use App\Services\Investment\PricingInvestmentProcessor;
use App\Services\Investment\PricingPayoutProcess;
use App\Services\Investment\PricingSubscription;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class ForexSchemaInvestormService extends Service
{
    const MIN_APP_VER = '1.0.0';
    const SLUG = 'investment';
    const TERM_CONVERSION = [
            SchemeTermTypes::YEARS => [
                InterestPeriod::YEARLY => 1,
                InterestPeriod::MONTHLY => 12,
                InterestPeriod::WEEKLY => 52,
                InterestPeriod::DAILY => 365,
                InterestPeriod::HOURLY => 8760,
            ],
            SchemeTermTypes::MONTHS => [
                InterestPeriod::MONTHLY => 1,
                InterestPeriod::WEEKLY => 4,
                InterestPeriod::DAILY => 30,
                InterestPeriod::HOURLY => 720,
            ],
            SchemeTermTypes::WEEKS => [
                InterestPeriod::WEEKLY => 1,
                InterestPeriod::DAILY => 7,
                InterestPeriod::HOURLY => 168,
            ],
            SchemeTermTypes::DAYS => [
                InterestPeriod::DAILY => 1,
                InterestPeriod::HOURLY => 24,
            ],
            SchemeTermTypes::HOURS => [
                InterestPeriod::HOURLY => 1,
            ]
        ];

    const INTERVALS = [
        InterestPeriod::HOURLY => 1,
        InterestPeriod::DAILY => 24,
        InterestPeriod::WEEKLY => 168,
        InterestPeriod::MONTHLY => 720,
        InterestPeriod::YEARLY => 8760,
    ];

    private $ivProcessor;
    private $payoutProcess;

    public function __construct()
    {
        $this->ivProcessor = new PricingInvestmentProcessor();
        $this->payoutProcess = new PricingPayoutProcess();
    }

    public function processSubscriptionDetails($input, $ivScheme, $investAmount,$discount,$weekly_payout_amount,$swap_free_amount): array
    {
//        dd($input,$ivScheme,$investAmount);
        $investmentProcessor = new PricingSubscription();
        return $investmentProcessor->setScheme($ivScheme)
            ->setUser(auth()->user())
            ->setInvestAmount($investAmount)
            ->setDiscountAmount($discount)
//            ->setLeverageAmount($leverage_amount)
//            ->setDaysToPassAmount($days_to_pass_amount)
//            ->setProfitSplitAmount($profit_split_amount)
            ->setPayoutAmount($weekly_payout_amount)
            ->setSwapFreeAmount($swap_free_amount)
//            ->setSource($input['source'])
            ->setCurrency($input['currency'])
            ->setInput($input)
            ->generateNewInvestmentDetails();
    }

    public function processSubscriptionDetailsForMigration($userId ,$input, $ivScheme, $investAmount): array
    {
//        dd($input,$ivScheme,$investAmount);
        $user = User::find($userId);
        $investmentProcessor = new IvSubscription();
        return $investmentProcessor->setScheme($ivScheme)
            ->setUser($user)
            ->setInvestAmount($investAmount)
            ->setSource($input['source'])
            ->setCurrency($input['currency'])
            ->generateNewInvestmentDetails();
    }

    public function confirmSubscription($details): ForexSchemaInvestment
    {
        return $this->ivProcessor->setDetails($details)->processInvestment();
    }
    public function confirmSubscriptionForMigration($details): ForexSchemaInvestment
    {
        return $this->ivProcessor->setDetails($details)->processInvestmentForMigration();
    }
    
    public function approveInvestment($ivID)
    {
        $ivInvestment = ForexSchemaInvestment::findOrFail($ivID);
        //        dd($ivInvestment);
        if (filled($ivInvestment)) {
            try {
                
//            $this->wrapInTransaction(function ($ivInvestment){
                $this->approveSubscription($ivInvestment, '', '');
//                    try {
//                        ProcessEmail::dispatch('investment-approved-customer', data_get($ivInvestment, 'user'), null, $ivInvestment);
//                        ProcessEmail::dispatch('investment-approved-admin', data_get($ivInvestment, 'user'), null, $ivInvestment);
//                    } catch (\Exception $e) {
//                        save_mailer_log($e, 'investment-placed');
//                    }
//            }, $ivInvestment);
                return true;
            } catch (\Exception $e) {
                throw ValidationException::withMessages(['invest' => 'Some error occurred! please try again']);
            }
        }
        throw ValidationException::withMessages(['invest' => 'Some error occurred! please try again']);
    }

    public function approveSubscription(ForexSchemaInvestment $invest, $remarks = null, $note=null)
    {
        return $this->ivProcessor->approveInvestment($invest, $remarks, $note);
    }

    public function approveSubscriptionForMigration(ForexSchemaInvestment $invest, $remarks = null, $note=null)
    {
        return $this->ivProcessor->approveInvestmentForMigration($invest, $remarks, $note);
    }

    public function processInvestmentProfit(ForexSchemaInvestment $invest)
    {
//        dd($invest,'processInvestmentProfit');
        if ($invest->status == InvestmentStatus::ACTIVE) {
            $transactionProcessor = new IvProfitCalculator();
            $transactionProcessor->setInvest($invest)
                ->calculateProfit();
        }
    }

    public function cancelSubscription(ForexSchemaInvestment $invest)
    {
        return $this->ivProcessor->cancelInvestment($invest);
    }

    public function proceedPayout($user_id, $profits)
    {
        $this->payoutProcess->setUser($user_id)
                ->setPayout($profits)
                ->payProfits();
    }
    public function proceedPayoutByCron($user_id,$invest_id, $profits)
    {
        $this->payoutProcess->setUser($user_id)
                ->setInvest($invest_id)
                ->setPayout($profits)
                ->payProfitsByCron();
    }

    public function processCompleteInvestment(ForexSchemaInvestment $invest)
    {
        $this->payoutProcess->completeInvest($invest);
    }
}
