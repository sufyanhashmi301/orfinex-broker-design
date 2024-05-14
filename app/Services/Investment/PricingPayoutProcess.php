<?php


namespace App\Services\Investment;

use App\Enums\Boolean;
use App\Enums\ActionType;
use App\Enums\ProfitPayout;
use App\Enums\LedgerTnxType;
use App\Enums\InvestmentStatus;
use App\Enums\TransactionCalcType;

use App\Models\ForexTrading;


use App\Traits\ForexApi;
use Carbon\Carbon;
use Brick\Math\BigDecimal;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PricingPayoutProcess
{
    use ForexApi;
    private $user_id;
    private $invest_id;
    private $profits;

    public function setUser(int $user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }
    public function setInvest(int $invest_id)
    {
        $this->invest_id = $invest_id;
        return $this;
    }

    public function setPayout(array $profits)
    {
        $this->profits = $profits;
        return $this;
    }

    public function payoutMode()
    {
        return sys_settings('iv_profit_payout', ProfitPayout::EVERYTIME);
    }

    public function payoutThreshold()
    {
        return (float)sys_settings('iv_profit_payout_amount', 0);
    }

    public function hasThreshold()
    {
        return ($this->payoutMode() == ProfitPayout::THRESHOLD && BigDecimal::of($this->payoutThreshold())->compareTo(0) == 1) ? true : false;
    }

    public function payProfits()
    {
        $batch = time();
//dd($this->hasThreshold());
        if ($this->hasThreshold()) {
            $threshold = $this->payoutThreshold();
            $profits = IvProfit::findMany($this->profits);

            if (!blank($profits)) {
                $amount = $profits->sum('amount');
                if (BigDecimal::of($amount)->compareTo($threshold) == -1) return;

                $ledger = $this->makeLedgerEntry($amount, $batch, $this->profits);

                if (!blank($ledger)) {
                    IvProfit::whereIn('id', $this->profits)->update(['payout' => $ledger->reference]);
                }
            }
        } else {
//            dd($this->profits);
            foreach ($this->profits as $profit_id) {
                $profit = IvProfit::find($profit_id);
                if (!blank($profit)) {
                    $ledger = $this->makeLedgerEntry($profit->amount, $batch, $profit);

                    if (!blank($ledger)) {
                        $profit->payout = $ledger->reference;
                        $profit->save();
                    }
                }
            }
        }
    }
    public function payProfitsByCron()
    {
        $batch = time();
//dd($this->hasThreshold());

//            $threshold = $this->payoutThreshold();
            $profits = IvProfit::findMany($this->profits);

            if (!blank($profits)) {
                $amount = $profits->sum('amount');
//                if (BigDecimal::of($amount)->compareTo($threshold) == -1) return;

                $ledger = $this->makeLedgerEntryByCron($amount, $batch, $this->profits);

//                if (!blank($ledger)) {
//                    IvProfit::whereIn('id', $this->profits)->update(['payout' => $ledger->reference]);
//                }
            }

    }

    private function makeLedgerEntry($amount, $batch = null, $profit = null)
    {
        $batch = (empty($batch)) ? time() : $batch;
        $data = [
            'ivx' => generate_unique_ivx(IvLedger::class, 'ivx'),
            'user_id' => $this->user_id,
            'type' => LedgerTnxType::PROFIT,
            'calc' => TransactionCalcType::CREDIT,
            'amount' => $amount,
            'fees' => 0,
            'total' => to_sum($amount, 0),
            'currency' => base_currency(),
            'desc' => "Profit Earned",
            'invest_id' => (isset($profit->invest_id)) ? $profit->invest_id : 0,
            'reference' => $batch,
            'meta' => ($this->toMetaData($profit)) ? json_encode($this->toMetaData($profit)) : null,
            'source' => AccType('invest'),
            'created_at' => Carbon::now(),
        ];

        $ledger = new IvLedger();
        $ledger->fill($data);
        $ledger->save();

        $this->updateBalance($ledger->amount);

        return $ledger;
    }



    private function updateBalance($amount, $type = null)
    {
        if (BigDecimal::of($amount)->compareTo(0) != 1) return;

        $type = (empty($type)) ? 'add' : $type;
        $account = get_user_account($this->user_id, AccType('invest'));
        $account->amount = ($type == 'add') ? to_sum($account->amount, $amount) : to_minus($account->amount, $amount);
        $account->save();
    }


}
