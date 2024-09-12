<?php

namespace App\Console\Commands;

use App\Enums\InvestmentStatus;
use App\Models\ForexSchemaInvestment;
use App\Services\ForexApiService;
use Brick\Math\BigDecimal;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DailyDrawDownCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:drawdown';

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
//        $updateUserApiResponse = $this->enableAccount(1065318);
//        dd($updateUserApiResponse->object());
//        $updateUserApiResponse = $this->disableAccount(1065318);
//        if (($updateUserApiResponse ? $updateUserApiResponse->status() == 200 && isset($updateUserApiResponse->object()->Login) : false)) {
//            dd($updateUserApiResponse->object());
////            dd(isset($updateUserApiResponse->object()->Login));
//        }
//        dd('s');
        $investments = ForexSchemaInvestment::where('status',InvestmentStatus::ACTIVE)
//            ->where('id',2)
            ->get();
//        dd($investments);
        foreach ($investments as $investment){
            $response['success'] = $this->forexApiService->getUserByLogin([
                'login' => $investment->login
            ]);
//           dd($getUserResponse->object());
            if ($response['success']) {
                $resData = $response['result'];
                //update max balance
                $investment =  $this->updateMaxBalance($resData,$investment);

                $this->checkMaxDrawdown($resData,$investment);
                $this->checkDailyDrawdown($resData,$investment);
                if(Carbon::parse($investment->equity_cal_at) < Carbon::now()->subDay()){
                    //2023-06-22 00:10:53 < 2023-05-22 00:11:53
                    $investment = $this->updateSnapShot($resData, $investment);
                }
            }else{

                echo "violated: ". $investment->login."\n";
                $investment->status = InvestmentStatus::VIOLATED;
                $investment->violated_at = Carbon::now();
//                $pricingInvestment->drawdown_reason = __('Maximum Loss Limit');
                $investment->save();
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
    public function updateSnapShot($resData,$pricingInvestment)
    {
        $pricingInvestment->snap_balance = $resData->balance;
        $pricingInvestment->snap_equity = $resData->equity;
        $pricingInvestment->snap_floating = $resData->Floating;
        $pricingInvestment->equity_cal_at = Carbon::now();
        $pricingInvestment->save();

        return $pricingInvestment;
    }
    public function checkMaxDrawdown($resData,$pricingInvestment)
    {
        if( BigDecimal::of($resData->equity)->isLessThanOrEqualTo(to_minus($pricingInvestment->max_balance, $pricingInvestment->max_drawdown_limit))) {
            //103414.74 <= 107589.79 - 2500
            //103414.74 <= 105089.79

//            $response = $this->disableAccount($pricingInvestment->login);
//            if ($response->status() == 200 && $response->successful() && $response->object()->ResponseCode == 0) {

                $pricingInvestment->current_equity = $resData->equity;
                $pricingInvestment->current_balance = $resData->balance;
                $pricingInvestment->status = InvestmentStatus::VIOLATED;
                $pricingInvestment->violated_at = Carbon::now();
                $pricingInvestment->drawdown_reason = __('Maximum Loss Limit');
                $pricingInvestment->save();
//            }

        }
    }
    public function checkDailyDrawdown($resData,$pricingInvestment)
    {
        if( BigDecimal::of($resData->equity)->isLessThanOrEqualTo(BigDecimal::of(0)) || BigDecimal::of($resData->equity)->isLessThanOrEqualTo(to_minus($pricingInvestment->snap_equity, $pricingInvestment->daily_drawdown_limit))) {
            $response = $this->disableAccount($pricingInvestment->login);
            if ($response->status() == 200 && $response->successful() && $response->object()->ResponseCode == 0) {

                $pricingInvestment->current_equity = $resData->equity;
                $pricingInvestment->current_balance = $resData->balance;
                $pricingInvestment->status = InvestmentStatus::VIOLATED;
                $pricingInvestment->violated_at = Carbon::now();
                $pricingInvestment->drawdown_reason = __('Maximum Daily Loss Limit');
                $pricingInvestment->save();
            }
        }
    }
}
