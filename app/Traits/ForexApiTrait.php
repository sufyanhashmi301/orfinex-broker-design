<?php

namespace App\Traits;

use App\Enums\ForexAccountStatus;
use App\Models\ForexAccount;
use Brick\Math\BigDecimal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;


trait ForexApiTrait
{

    public function getUserApi($login)
    {
        $getUserUrl = config('forextrading.getUserUrl');
        $auth = config('forextrading.auth');

        $dataArray = array(
            'Login' => (int)$login,
            'auth' => (int)$auth,
        );
        $response = $this->sendApiRequest($getUserUrl, $dataArray);
//            dd($response->object(),$response->status());
        if (isset($response)){
//            dd('s');
            if ($response->status() == 200) {
//                dd('s');
                if (isset($response->object()->data->Login)) {
                    return $response;
                }
            }
    }
//        else {
//            echo "connection error"."\n";
//            throw ValidationException::withMessages([
//                'invalid' => __('Some thing wrong! Please reload the page and try again!')
//            ]);
//        }
    }
    public function sendApiRequest($URL, $dataArray)
    {
        try {
            return Http::retry(3, 100)->get($URL, $dataArray);
        } catch (\GuzzleHttp\Exception\RequestException $exception) {
            return $exception;
            // Handle request exceptions
        }
    }

    public function isValidForexAccount($login)
    {
        $getUserUrl = config('forextrading.getUserUrl');
        $auth = config('forextrading.auth');

        $dataArray = array(
            'Login' => $login,
            'auth' => $auth,
        );

        $response = $this->sendApiRequest($getUserUrl, $dataArray);
        if ($response->status() == 200) {
            if (isset($response->object()->data->Login)) {
                return true;
            } else {
                $message = __('The forex account :login is not exist in MT5!.please choose valid account', ['login' => $login]);
                notify()->error($message, 'Error');
            }
        }
        throw ValidationException::withMessages([
            'invalid' => __('Some thing wrong! Please reload the page and try again!')
        ]);
    }
//
    public function getForexAccountBalance($login)
    {
//        dd($login);
        $getUserResponse = $this->getUserApi($login);
        if ($getUserResponse->status() == 200) {
            if (isset($getUserResponse->object()->data->Login)) {
                return  BigDecimal::of($getUserResponse->object()->data->Balance)->plus($getUserResponse->object()->data->Floating);
            } else {
                throw ValidationException::withMessages([
                    'invalid' => __('The forex account :login is not exist in MT5!.please choose valid account', ['login' => $login])
                ]);
            }
        }
        throw ValidationException::withMessages([
            'invalid' => __('Some thing wrong! Please reload the page and try again!')
        ]);
    }

    public function forexWithdraw($login, $amount, $comment)
    {

//        $userAccount = ForexTrading::find($transaction->account_from);
        $withdrawUrl = config('forextrading.withdrawUrl');
        $auth = config('forextrading.auth');

        $dataArray = [
            'Login' => $login,
            'Withdraw' => $amount,
            'Comment' => $comment,
            'auth' => $auth,
        ];
//        dd($userAccount,$dataArray);
        $withdrawResponse = $this->sendApiRequest($withdrawUrl, $dataArray);
//        dd($withdrawResponse->object());
        if ($withdrawResponse->status() == 200 && $withdrawResponse->object()->data == 0) {
            return true;
        }
        else{
            $message = __('You do not have enough money! Kindly select valid amount', ['login'=>$login]);
            notify()->error($message, 'Error');
        }
    }

    public function forexDeposit($login, $amount, $comment)
    {

//        $userAccount = ForexTrading::find($transaction->account_from);
        $url = config('forextrading.depositUrl');
        $auth = config('forextrading.auth');

        $dataArray = [
            'Login' => $login,
            'Deposit' => $amount,
            'Comment' => $comment,
            'auth' => $auth,
        ];
//        dd($url,$dataArray);
        $response = $this->sendApiRequest($url, $dataArray);
//        dd($userAccount,$response);
        if ($response->status() == 200 && $response->object()->data == 0) {
            return true;
        } else {
            $message = __('The forex account :login is not exist in MT5!.please choose valid account', ['login' => $login]);
            notify()->error($message, 'Error');
        }
    }
//
//    public function getTotalHistory($login, $from, $to)
//    {
//        $url = config('forextrading.totalHistoryUrl');
//        $auth = config('forextrading.auth');
//
//        $dataArray = array(
//            'Login' => $login,
//            'auth' => $auth,
//            'From' => $from,
//            'To' => $to,
//        );
////        dd($dataArray);
//        return $this->sendApiRequest($url, $dataArray);
//
//    }
//
//    public function getPageHistory($login, $from, $to)
//    {
//        $url = config('forextrading.pageHistoryUrl');
//        $auth = config('forextrading.auth');
//
//        $dataArray = array(
//            'Login' => $login,
//            'auth' => $auth,
//            'From' => $from,
//            'To' => $to,
//            'Offset' => 0,
//            'Total' => 100,
//        );
////        dd($dataArray);
//        return $this->sendApiRequest($url, $dataArray);
//
//    }
//
    public function updateMainPassword($login, $password)
    {
        $url = config('forextrading.mainPasswordChangeUrl');
        $auth = config('forextrading.auth');

        $dataArray = array(
            'Login' => $login,
            'auth' => $auth,
            'MainPassword' => $password,
        );
//        dd($dataArray);
        return $this->sendApiRequest($url, $dataArray);

    }

    public function disableAccount($login)
    {
        $url = config('forextrading.disableAccountUrl');
        $auth = config('forextrading.auth');

        $dataArray = array(
            'Login' => $login,
            'auth' => $auth,
        );
//        dd($dataArray);
        return $this->sendApiRequest($url, $dataArray);

    }

    public function enableAccount($login)
    {
        $url = config('forextrading.enableAccountUrl');
        $auth = config('forextrading.auth');

        $dataArray = array(
            'Login' => $login,
            'auth' => $auth,
        );
//        dd($dataArray);
        return $this->sendApiRequest($url, $dataArray);

    }
//
//    public function getPageDeal($login, $from, $to)
//    {
//        $url = config('forextrading.pageDealUrl');
//        $auth = config('forextrading.auth');
//
//        $dataArray = array(
//            'Login' => $login,
//            'auth' => $auth,
//            'From' => $from,
//            'To' => $to,
//            'Offset' => 0,
//            'Total' => 100,
////            'Comment' => "agent '553862' - #1431717",
//        );
////        dd($dataArray);
//        return $this->sendApiRequest($url, $dataArray);
//
//    }
//
//
    public function syncForexAccounts($userID=null)
    {
        if(!isset($userID))
            $userID = auth()->user()->id;

        $realAccounts = ForexAccount::where('user_id',$userID)
            ->where('status', ForexAccountStatus::Ongoing)
            ->get();

        foreach ($realAccounts as $account) {
//            dd($account);
            $getUserResponse = $this->getUserApi($account->login);
//            dd($getUserResponse);
//           dd($getUserResponse->object(),$getUserResponse->object()->data->Login);
            if(isset($getUserResponse)) {
//                dd($getUserResponse->object(),$getUserResponse->object()->data->Login);

                if ($getUserResponse->status() == 200 && isset($getUserResponse->object()->data->Login)) {
                    $this->updateUserAccount($getUserResponse);
                }
            }
        }

    }

    public function updateUserAccount($getUserResponse, $lastDeposit = false)
    {
        $resData = $getUserResponse->object()->data;
//        dd($resData);
        if (isset($resData->Login)) {
            $forexTrading = ForexAccount::where('login', $resData->Login)->first();
//        $forexTrading->account_name = $resData->Name;
            $forexTrading->leverage = $resData->Leverage;
//      $forexTrading->email = $resData->Email;
            $forexTrading->balance = $resData->Balance;
            $forexTrading->equity = $resData->Equity;
            $forexTrading->agent = $resData->Agent;
            $forexTrading->free_margin = $resData->MarginFree;
//            $forexTrading->margin = $resData->Margin;
            $forexTrading->group = $resData->Group;

            $forexTrading->save();
        }
    }

//    public function syncPricingAccount($login)
//    {
////            dd($account);
//        $getUserResponse = $this->getUserApi($login);
////           dd($getUserResponse->object(),$getUserResponse->object()->data->Login);
//        if ($getUserResponse->status() == 200 && isset($getUserResponse->object()->data->Login)) {
//            $this->updatePricingInvestmentAccount($getUserResponse);
//        }
//
//
//    }
//
//    public function updatePricingInvestmentAccount($getUserResponse)
//    {
//        $resData = $getUserResponse->object()->data;
////        dd($resData);
//        if (isset($resData->Login)) {
//            $pricingInvestment = PricingInvestment::where('login', $resData->Login)->first();
//            $pricingInvestment->leverage = $resData->Leverage;
//            $pricingInvestment->current_balance = $resData->Balance;
//            $pricingInvestment->current_equity = $resData->Equity;
//
//            $profit = 0;
//            if(to_minus($resData->Equity,$pricingInvestment->amount_allotted) > 0){
//                $profit = to_minus($resData->Equity,$pricingInvestment->amount_allotted);
//            }
//            $pricingInvestment->profit = $profit;
//            $pricingInvestment->group = $resData->Group;
//            $pricingInvestment->save();
//        }
//    }
//
//    public function updateLastDeposit($login)
//    {
//
//        $forexTrading = ForexTrading::where('login', $login)->first();
//        $forexTrading->last_deposit = Carbon::now();
//        $forexTrading->save();
////        }
//    }
//    public function getPageDealForIBProfits($login, $from, $to,$start,$end)
//    {
//        $url = config('forextrading.pageDealUrl');
//        $auth = config('forextrading.auth');
//
//        $dataArray = array(
//            'Login' => $login,
//            'auth' => $auth,
//            'From' => $from,
//            'To' => $to,
//            'Offset' => $start        ,
//            'Total' => $end,
////            'Comment' => "agent '553862' - #1431717",
//        );
////        dd($dataArray);
//        return $this->sendApiRequest($url, $dataArray);
//
//    }

}
