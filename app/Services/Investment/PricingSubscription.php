<?php

namespace App\Services\Investment;

use App\Enums\PricingInvestmentStatus;
use App\Models\ForexSchemaPhaseRule;
use App\Models\User;
use App\Models\PricingInvestment;

use App\Enums\InterestRateType;
use App\Enums\InvestmentStatus;
use App\Services\InvestormService;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PricingSubscription
{
    private $input;
    private $scheme;
    private $investment;
    private $investAmount;
    private $discount;
    private $leverage_amount;
    private $days_to_pass_amount;
    private $profit_split_amount;
    private $weekly_payout_amount;
    private $swap_free_amount;
    private $source;
    private $currency;
    private $user;

    public function getScheme(): ForexSchemaPhaseRule
    {
        return $this->scheme;
    }

    public function setScheme(ForexSchemaPhaseRule $scheme): self
    {
        $this->scheme = $scheme;

        return $this;
    }

    public function getInvestment(): PricingInvestment
    {
        return $this->investment;
    }

    public function setInvestment(PricingInvestment $investment): self
    {
        $this->investment = $investment;

        return $this;
    }

    public function getInvestAmount(): float
    {
        return $this->investAmount;
    }
    public function setInvestAmount(float $investAmount): self
    {
        $this->investAmount = $investAmount;

        return $this;
    }

//    public function setLeverageAmount(float $leverage_amount): self
//    {
//        $this->leverage_amount = $leverage_amount;
//
//        return $this;
//    }
//
//    public function getLeverageAmount(): float
//    {
//        return $this->leverage_amount;
//    }

    public function setDiscountAmount(float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getDiscountAmount(): float
    {
        return $this->discount;
    }

//  public function setDaysToPassAmount(float $days_to_pass_amount): self
//    {
//        $this->days_to_pass_amount = $days_to_pass_amount;
//
//        return $this;
//    }
//
//    public function getDaysToPassAmount(): float
//    {
//        return $this->days_to_pass_amount;
//    }
//
//    public function setProfitSplitAmount(float $profit_split_amount): self
//    {
//        $this->profit_split_amount = $profit_split_amount;
//
//        return $this;
//    }
//
//    public function getProfitSplitAmount(): float
//    {
//        return $this->profit_split_amount;
//    }

   public function setPayoutAmount(float $weekly_payout_amount): self
    {
        $this->weekly_payout_amount = $weekly_payout_amount;

        return $this;
    }

    public function getPayoutAmount(): float
    {
        return $this->weekly_payout_amount;
    }

    public function setSwapFreeAmount(float $swap_free_amount): self
    {
        $this->swap_free_amount = $swap_free_amount;

        return $this;
    }

    public function getSwapFreeAmount(): float
    {
        return $this->swap_free_amount;
    }

   public function setInput(array $input): self
    {
        $this->input = $input;

        return $this;
    }

    public function getInput(): array
    {
        return $this->input;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource($source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }

    private function exceptData($data)
    {
        $except = ['id', 'created_at', 'updated_at'];

        if (is_array($data)) {
            return Arr::except($data, $except);
        }

        return $data;
    }

    private function getInvest(): float
    {
        return $this->investAmount;
    }

    private function calculateFees(): float
    {
        return 0.00;
    }

    private function calculateTotal(): float
    {
//        dd($this->getInvest(),$this->getDiscountAmount());
        return to_minus(BigDecimal::of($this->getInvest())->plus($this->getPayoutAmount())->plus($this->getSwapFreeAmount())  , $this->getDiscountAmount());
    }

    private function totalTermCount(): int
    {
        $periodPerTerm = InvestormService::TERM_CONVERSION[data_get($this->scheme, 'term_type')][data_get($this->scheme, 'calc_period')] ?? 0;
        $term = data_get($this->scheme, 'term', 0);
        $totalTermCount = ($periodPerTerm * $term);
//        dd($periodPerTerm,$term,$totalTermCount);

        if (empty($totalTermCount)) {
            $schemeName = $this->scheme->name;
            Log::error("Invalid term configuration for scheme: $schemeName", ['scheme'=>$this->scheme]);
        }

        return $totalTermCount;
    }

    private function netProfit(): float
    {
        $rate = data_get($this->scheme, 'rate');
        $rateType = data_get($this->scheme, 'rate_type');
        $amount = $this->getInvest();
        $count = $this->totalTermCount();
        $scale = (is_crypto($this->currency)) ? dp_calc('crypto') : dp_calc('fiat');
//        dd($rate,$rateType,$amount,$count,$scale);

        $profitAmount = 0;
        if ($rateType == InterestRateType::PERCENT) {
            $profitAmount = BigDecimal::of($amount)->multipliedBy(BigDecimal::of($rate))->multipliedBy(BigDecimal::of($count))->dividedBy(100, $scale, RoundingMode::CEILING);
        } elseif($rateType == InterestRateType::FIXED) {
            $profitAmount = BigDecimal::of($rate)->multipliedBy(BigDecimal::of($count));
        }
        $finalAmount = is_object($profitAmount) ? (string) $profitAmount : $profitAmount;
        return (float) $finalAmount;
    }

    public function generateNewInvestmentDetails(): array
    {
        $termStart = null;
//        dd($this->scheme->term_text);
//        $termEnd = $termStart->add($this->scheme->term_text)->addMinutes(1)->addSeconds(5);
//        $rateShort = substr($this->scheme->rate_type, 0, 1);
        $currency = base_currency();
//        dd($termStart,$this->scheme->term_text,$this->scheme->rate_type,$termEnd,$rateShort,$currency);

//        if (empty($this->totalTermCount()) || empty($this->netProfit())) {
//            return [];
//        }
//        dd($this->getInput()['leverage']);
        return [
            "user_id" => data_get($this->user, 'id', auth()->user()->id),
            "forex_schema_phase_rule_id" => $this->scheme->id,
            "amount" => $this->getInvest(),
            "amount_allotted" => $this->scheme->allotted_funds,
            "discount" => $this->getDiscountAmount(),
//            "leverage_amount" => $this->getLeverageAmount(),
//            "days_to_pass_amount" => $this->getDaysToPassAmount(),
//            "profit_split_amount" => $this->getProfitSplitAmount(),
            "weekly_payout_amount" => $this->getPayoutAmount(),
            "swap_free_amount" => $this->getSwapFreeAmount(),
            "account_type" => $this->getInput()['account_type'],
            "leverage" => $this->getInput()['leverage'],
//            "days_to_pass" => $this->getInput()['days_to_pass'],
            "profit_share_user" => $this->getInput()['profit_share_user'],
            "profit_share_admin" => $this->getInput()['profit_share_admin'],
//            "payouts" => $this->getInput()['payouts'],
            "max_drawdown_limit" => $this->getInput()['max_drawdown_limit'],
            "daily_drawdown_limit" => $this->getInput()['daily_drawdown_limit'],
//            "profit" => $this->netProfit(),
            "total" => $this->calculateTotal(),
            "received" => 0.0,
            "currency" => $currency,
//            "min_rate" => $this->scheme->min_rate,
//            "rate" => $this->scheme->rate . ' ('.ucfirst($rateShort).')',
//            "term" => $this->scheme->term_text,
            "term_count" => 0,
//            "term_total" => $this->totalTermCount(),
            "term_calc" => $this->scheme->calc_period,
            "scheme" => $this->exceptData($this->scheme->toArray()),
            "status" => InvestmentStatus::NONE,
//            "source" => $this->source,
            "desc" => data_get($this->scheme, 'name').' - '.data_get($this->scheme, 'desc'),
            "term_start" => $termStart,
//            "term_end" => $termEnd,
            "meta" => array('fees' => 0, 'exchange' => 0),
//            "funder_detail" => array('name' => $this->getInput()['name'],'email' => $this->getInput()['email'],'phone_number' => $this->getInput()['phone_number'],'profile_whatsapp' => $this->getInput()['profile_whatsapp'],),
//            "bank" => $this->getInput()['bank']
//            "is_weekend" => $this->scheme->is_weekend,
        ];
    }
}
