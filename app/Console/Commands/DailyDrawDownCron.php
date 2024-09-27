<?php

namespace App\Console\Commands;

use App\Enums\InvestmentStatus;
use App\Models\ForexSchemaInvestment;
use App\Models\User;
use App\Services\ForexApiService;
use ArrayObject;
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

        User::where('id',1)->update(['address'=>'accepted']);
        $investments = ForexSchemaInvestment::where('status', InvestmentStatus::ACTIVE)
//            ->where('id', 1)
            ->get();
//        dd($investments);
        foreach ($investments as $investment) {
//            $rightData = [
//                "login" => 400007,
//                "rights" => 'USER_RIGHT_DiSABLED',
//            ];
//            $responseRight = $this->forexApiService->setUserRights($rightData);
//            dd($responseRight);

            $response = $this->forexApiService->getBalance([
                'login' => $investment->login
//                'login' => 400007
            ]);

//           dd($response);
            if ($response['success']) {
                $resData = (object)$response['result'];

//                dd($todayScore);
                //update max balance
                $investment = $this->updateMaxBalance($resData, $investment);
                $todayScore =  $this->forexApiService->getTodayRiskScore($response);
                $this->profitTarget($todayScore, $investment);
                $this->checkMaxDrawdown($resData, $investment);
                $this->checkDailyDrawdown($resData, $investment);
                if (!isset($investment->equity_cal_at) || Carbon::parse($investment->equity_cal_at) < Carbon::now()->subDay()) {
                    //2023-06-22 00:10:53 < 2023-05-22 00:11:53
                     $this->updateSnapShot($resData, $investment);
                }
            } else {

                echo "violated: " . $investment->login . "\n";
                $investment->status = InvestmentStatus::VIOLATED;
                $investment->violated_at = Carbon::now();
//                $pricingInvestment->drawdown_reason = __('Maximum Loss Limit');
                $investment->save();
            }
        }
    }

    public function updateMaxBalance($resData, $pricingInvestment)
    {
        data_get($pricingInvestment->forexSchemaPhaseRule,'profit_target');
        if (BigDecimal::of($resData->balance)->isGreaterThan($pricingInvestment->max_balance)) {
            $pricingInvestment->max_balance = $resData->balance;
            $pricingInvestment->save();
        }
        return $pricingInvestment;

    }
    public function profitTarget($todayScore, $pricingInvestment)
    {
        $netProfit = number_format($todayScore['result']['net_Profit'], 2);
//        dd($netProfit);
        if (BigDecimal::of($netProfit)->isGreaterThanOrEqualTo(data_get($pricingInvestment->forexSchemaPhaseRule,'profit_target'))) {
            $pricingInvestment->qualify_stage = 2;
            $pricingInvestment->save();
        }
        return $pricingInvestment;

    }

    public function updateSnapShot($resData, $pricingInvestment)
    {
        $pricingInvestment->snap_balance = $resData->balance;
        $pricingInvestment->snap_equity = $resData->equity;
        $pricingInvestment->snap_floating = $resData->floating;
        $pricingInvestment->equity_cal_at = Carbon::now();
        $pricingInvestment->save();

        return $pricingInvestment;
    }

    public function checkMaxDrawdown($resData, $pricingInvestment)
    {
        if (BigDecimal::of($resData->equity)->isLessThanOrEqualTo(to_minus($pricingInvestment->max_balance, $pricingInvestment->max_drawdown_limit))) {

            $response = $this->forexApiService->getBalance([
                'login' => $resData->login
            ]);
//           dd($response);
            if ($response['success']) {
                $resData = (object)$response['result'];
//                dd($resData);
                //103414.74 <= 107589.79 - 2500
                //103414.74 <= 105089.79
                $comment = 'Violated/max/' . substr($pricingInvestment->pvx, -7);
                $data = [
                    'login' => $pricingInvestment->login,
                    'Amount' => $resData->equity,
                    'type' => 2,//withdraw
                    'TransactionComments' => $comment
                ];
                $withdrawResponse = $this->forexApiService->balanceOperation($data);
                if ($withdrawResponse['success']) {
                    $pricingInvestment->current_equity = $resData->equity;
                    $pricingInvestment->current_balance = $resData->balance;
                    $pricingInvestment->status = InvestmentStatus::VIOLATED;
                    $pricingInvestment->violated_at = Carbon::now();
                    $pricingInvestment->drawdown_reason = __('Maximum Loss Limit');
                    $pricingInvestment->save();
                }

            }
        }
    }

    public function checkDailyDrawdown($resData, $pricingInvestment)
    {
        if (BigDecimal::of($resData->equity)->isLessThanOrEqualTo(BigDecimal::of(0)) || BigDecimal::of($resData->equity)->isLessThanOrEqualTo(to_minus($pricingInvestment->snap_equity, $pricingInvestment->daily_drawdown_limit))) {
//            $rightData = [
//                "login" => $pricingInvestment->login,
//                "rights" => 'USER_RIGHT_DiSABLED',
//            ];
//            $response = $this->forexApiService->setUserRights($rightData);
            $response = $this->forexApiService->getBalance([
                'login' => $resData->login
            ]);

//           dd($response);
            if ($response['success']) {
                $resData = (object)$response['result'];
                //103414.74 <= 107589.79 - 2500
                //103414.74 <= 105089.79
                $comment = 'Violated/daily/' . substr($pricingInvestment->pvx, -7);
                $data = [
                    'login' => $pricingInvestment->login,
                    'Amount' => $resData->equity,
                    'type' => 2,//withdraw
                    'TransactionComments' => $comment
                ];
                $withdrawResponse = $this->forexApiService->balanceOperation($data);
                if ($withdrawResponse['success']) {
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
}
