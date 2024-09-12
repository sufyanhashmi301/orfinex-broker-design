<?php

namespace App\Console\Commands;

use App\Enums\ForexTradingStatus;
use App\Enums\InvestmentStatus;
use App\Enums\PricingInvestmentStatus;
use App\Models\ForexSchemaInvestment;
use App\Models\ForexTrading;
use App\Models\PricingInvestment;
use App\Services\ForexApiService;
use App\Traits\ForexApi;
use Brick\Math\BigDecimal;
use Illuminate\Console\Command;

class MaxDrawDownCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'max:drawdown';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $forexApiService;
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function __construct(ForexApiService $forexApiService)
    {
        $this->forexApiService = $forexApiService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $investments = ForexSchemaInvestment::where('status',InvestmentStatus::ACTIVE)->get();
        foreach ($investments as $investment){
            $response['success'] = $this->forexApiService->getUserByLogin([
                'login' => $investment->login
            ]);
//           dd($getUserResponse->object());
            if ($response['success']) {
                $resData = $response['result'];
                //update max balance
                $investment =  $this->updateMaxBalance($resData,$investment);
                $this->checkDrawdown($resData,$investment);
            }
        }
    }

    public function updateMaxBalance($resData,$pricingInvestment)
    {
            if( BigDecimal::of($resData->balance)->isGreaterThan($pricingInvestment->max_balance)) {
                $pricingInvestment->max_balance = $resData->balance;
                $pricingInvestment->save();
            }
            return $pricingInvestment;

    }
    public function checkDrawdown($resData,$pricingInvestment)
    {

            if(BigDecimal::of($resData->equity)->isLessThanOrEqualTo(0) || BigDecimal::of($resData->equity)->isLessThanOrEqualTo(to_minus($pricingInvestment->max_balance,$pricingInvestment->max_drawdown_limit))) {
//                $response = $this->disableAccount($pricingInvestment->login);
//                if ($response->status() == 200 && $response->successful() && $response->object()->ResponseCode == 0) {

                    $pricingInvestment->status = InvestmentStatus::VIOLATED;
                    $pricingInvestment->drawdown_reason = __('Maximum Loss Limit');
                    $pricingInvestment->save();

//                }

            }
    }

}
