<?php

namespace App\Traits;


use App\Enums\ForexTradingAccountTypesStatus;
use App\Enums\ForexTradingStatus;
use App\Enums\PricingInvestmentStatus;
use App\Models\AccountType;
use App\Models\ForexTrading;
use App\Models\PricingInvestment;
use Brick\Math\BigDecimal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;


trait ForexApi
{
    public function syncForexAccounts($userID=null)
    {
        if(!isset($userID))
            $userID = auth()->user()->id;

        $realAccounts = ForexTrading::whereDoesntHave('accountType.accountGroup', function ($q) {
            $q->where('account_investment_category_id', 3);
        })
            ->where('user_id',$userID)
            ->realActiveAccount($userID)
            ->get();

        foreach ($realAccounts as $account) {
//            dd($account);
            $getUserResponse = $this->getUserApi($account->login);
//           dd($getUserResponse->object(),$getUserResponse->object()->Login);
            if ($getUserResponse->status() == 200 && isset($getUserResponse->object()->Login)) {
                $this->updateUserAccount($getUserResponse);
            }
        }

    }

    public function updateUserAccount($getUserResponse, $lastDeposit = false)
    {
        $resData = $getUserResponse->object();
//        dd($resData);
        if (isset($resData->Login)) {
            $forexTrading = ForexTrading::where('login', $resData->Login)->first();
//        $forexTrading->account_name = $resData->Name;
//            $forexTrading->leverage = $resData->Leverage;
//      $forexTrading->email = $resData->Email;
            $forexTrading->balance = $resData->Balance;
            $forexTrading->equity = $resData->Equity;
//            $forexTrading->agent = $resData->Agent;
//            $forexTrading->free_margin = $resData->MarginFree;
//            $forexTrading->margin = $resData->Margin;
//            $forexTrading->group = $resData->Group;
            if ($lastDeposit)
                $forexTrading->last_deposit = Carbon::now();

            $forexTrading->save();
        }
    }

    public function syncPricingAccount($login)
    {
        $getUserResponse = $this->getUserApi($login);
        if ($getUserResponse->status() == 200 && isset($getUserResponse->object()->Login) && $getUserResponse->object()->Login != 0) {
            $this->updatePricingInvestmentAccount($getUserResponse);
        }else{
            $invest = PricingInvestment::where('login',$login)->first();
            $invest->status = PricingInvestmentStatus::VIOLATED;
            $invest->violated_at = Carbon::now();
            $invest->save();
        }
    }

    public function updatePricingInvestmentAccount($getUserResponse)
    {
        $resData = $getUserResponse->object();
//        dd($resData);
        if (isset($resData->Login) && $resData->Login != 0) {
            $pricingInvestment = PricingInvestment::where('login', $resData->Login)->first();
//            $pricingInvestment->leverage = $resData->Leverage;
            $pricingInvestment->current_balance = $resData->Balance;
            $pricingInvestment->current_equity = $resData->Equity;

            $profit = 0;
            if(to_minus($resData->Equity,$pricingInvestment->amount_allotted) > 0){
                $profit = to_minus($resData->Equity,$pricingInvestment->amount_allotted);
            }
            $pricingInvestment->profit = $profit;
//            $pricingInvestment->group = $resData->Group;
            $pricingInvestment->save();
        }
    }

    public function updateLastDeposit($login)
    {

        $forexTrading = ForexTrading::where('login', $login)->first();
        $forexTrading->last_deposit = Carbon::now();
        $forexTrading->save();
//        }
    }

    public function getUserApi($login)
    {
        $getUserUrl = config('forextrading.getUserUrl');
        $dataArray = array(
            'Login' => (int)$login,
        );
        $response = $this->sendApiRequest($getUserUrl, $dataArray);
//            dd($response->object(),$response->status());
        if ($response->status() == 200) {
            if (isset($response->object()->Login) ) {
                return $response;
            } else {
                $response = $this->sendApiRequest($getUserUrl, $dataArray);
                if ($response->status() == 200) {
                    if (isset($response->object()->Login) && $response->object()->Login != 0) {
                        return $response;
                    } else {
                        ForexTrading::where('login', $login)->delete();
                        return $response;
                    }
                }
                return $response;
            }

        } else {
            throw ValidationException::withMessages([
                'invalid' => __('Some thing wrong! Please reload the page and try again!')
            ]);
        }
    }

    public function isValidForexAccount($login)
    {
        $getUserUrl = config('forextrading.getUserUrl');


        $dataArray = array(
            'Login' => $login,

        );

        $response = $this->sendApiRequest($getUserUrl, $dataArray);
        if ($response->status() == 200) {
            if (isset($response->object()->Login)) {
                return true;
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

    public function getForexAccountBalance($login)
    {
//        dd($login);
        $getUserResponse = $this->getUserApi($login);
        if ($getUserResponse->status() == 200) {
            if (isset($getUserResponse->object()->Login)) {
                return  BigDecimal::of($getUserResponse->object()->Balance)->plus($getUserResponse->object()->Floating);
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


        $dataArray = [
            'Login' => $login,
            'Amount' => -$amount,
            'Comment' => $comment,

        ];
//        dd($userAccount,$dataArray);
        $response = $this->sendApiPostRequest($withdrawUrl, $dataArray);
//        dd($withdrawResponse->object());
        if ($response->status() == 200 && $response->object() == 10009) {
            return true;
        }
        else{
            throw ValidationException::withMessages([
                'invalid' => __('You do not have enough money! Kindly select valid amount', ['login'=>$login])
            ]);
        }
    }

    public function forexDeposit($login, $amount, $comment)
    {
//        $userAccount = ForexTrading::find($transaction->account_from);
        $url = config('forextrading.depositUrl');

        $dataArray = [
            'Login' => $login,
            'Amount' => $amount,
            'Comment' => $comment,

        ];
//        dd($url,$dataArray);
        $response = $this->sendApiPostRequest($url, $dataArray);
//        dd($userAccount,$response);
        if ($response->status() == 200 && $response->object() == 10009) {
            return true;
        } else {
            throw ValidationException::withMessages([
                'invalid' => __('The forex account :login is not exist in MT5!.please choose valid account', ['login' => $login])
            ]);
        }
    }

    public function getTotalHistory($login, $from, $to)
    {
        $url = config('forextrading.totalHistoryUrl');


        $dataArray = array(
            'Login' => $login,

            'From' => $from,
            'To' => $to,
        );
//        dd($dataArray);
        return $this->sendApiRequest($url, $dataArray);

    }

    public function getPageHistory($login, $from, $to)
    {
        $url = config('forextrading.pageHistoryUrl');


        $dataArray = array(
            'Login' => $login,

            'From' => $from,
            'To' => $to,
            'Offset' => 0,
            'Total' => 100,
        );
//        dd($dataArray);
        return $this->sendApiRequest($url, $dataArray);

    }

    public function updateMainPassword($login, $password)
    {
        $url = config('forextrading.resetMasterPasswordUrl');


        $dataArray = array(
            'Login' => $login,
            'Password' => $password,
        );
//        dd($dataArray);
        return $this->sendApiPostRequest($url, $dataArray);

    }
    public function updateInvestorPassword($login, $password)
    {
        $url = config('forextrading.resetInvestorPasswordUrl');

        $dataArray = array(
            'Login' => $login,
            'Password' => $password,
        );
//        dd($dataArray);
        return $this->sendApiPostRequest($url, $dataArray);

    }
    public function disableAccount($login)
    {
        $url = config('forextrading.setRightsUrl');
        $dataArray = array(
            'Login' => $login,
            'Rights' => 'USER_RIGHT_NONE',
        );
//        dd($dataArray);
        return $this->sendApiPostRequest($url, $dataArray);

    }
    public function enableAccount($login)
    {
        $url = config('forextrading.enableAccountUrl');


        $dataArray = array(
            'Login' => $login,

        );
//        dd($dataArray);
        return $this->sendApiPostRequest($url, $dataArray);

    }

    public function getPageDeal($login, $from, $to)
    {
        $url = config('forextrading.pageDealUrl');


        $dataArray = array(
            'Login' => $login,
            'From' => $from,
            'To' => $to,
            'Offset' => 0,
            'Total' => 100,
//            'Comment' => "agent '553862' - #1431717",
        );
//        dd($dataArray);
        return $this->sendApiRequest($url, $dataArray);

    }

    public function sendApiRequest($URL, $dataArray)
    {
        try {
            return Http::retry(3, 100)->get($URL, $dataArray);
        } catch (\GuzzleHttp\Exception\RequestException $exception) {
            return $exception;
        }
    }
    public function sendApiPostRequest($URL, $dataArray)
    {
        try {
            return Http::retry(3, 100)->post($URL, $dataArray);
        } catch (\GuzzleHttp\Exception\RequestException $exception) {
            return $exception;
        }
    }

    public function getPageDealForIBProfits($login, $from, $to,$start,$end)
    {
        $url = config('forextrading.pageDealUrl');


        $dataArray = array(
            'Login' => $login,

            'From' => $from,
            'To' => $to,
            'Offset' => $start        ,
            'Total' => $end,
//            'Comment' => "agent '553862' - #1431717",
        );
//        dd($dataArray);
        return $this->sendApiPostRequest($url, $dataArray);

    }

}
