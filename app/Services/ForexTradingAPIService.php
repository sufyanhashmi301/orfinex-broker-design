<?php

namespace App\Services;


use App\Models\ForexTrading;
use Brick\Math\BigDecimal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class ForexTradingAPIService extends Service
{

//
//    private $ivProcessor;
//    private $payoutProcess;

    public function __construct()
    {
//        $this->ivProcessor = new TokenInvestmentProcessor();
//        $this->payoutProcess = new TokenPayoutProcess();
    }


    public function syncForexAccounts()
    {
        $realAccounts = ForexTrading::whereDoesntHave('accountType.accountGroup', function ($q) {
            $q->where('account_investment_category_id', 3);
        })
            ->realActiveAccount()
            ->get();
        $demoAccounts = ForexTrading::whereDoesntHave('accountType.accountGroup', function ($q) {
            $q->where('account_investment_category_id', 3);
        })
            ->demoActiveAccount()
            ->get();
        foreach ($realAccounts as $account) {
            $getUserResponse = $this->getUserApi($account->login);
//           dd($getUserResponse->object(),$getUserResponse->object()->data->Login);
            if ($getUserResponse->status() == 200 && isset($getUserResponse->object()->Login)) {
                $this->updateUserAccount($getUserResponse);
            }
        }
        foreach ($demoAccounts as $account) {
            $getUserResponse = $this->getUserApi($account->login);
            if ($getUserResponse->status() == 200) {
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
            'Login' => $login,

        );
        return $this->sendApiRequest($getUserUrl, $dataArray);

    }
    public function isValidForexAccount($login)
    {
        $getUserUrl = config('forextrading.getUserUrl');


        $dataArray = array(
            'Login' => $login,

        );
        $status = false;
        $response = $this->sendApiRequest($getUserUrl, $dataArray);
        if ($response->status() == 200) {
            if (isset($response->object()->Login))
                $status = true;
            return $status;
        }
        return $status;
    }
    public function getForexAccountBalance($login)
    {
//        dd($login);
        $getUserResponse = $this->getUserApi($login);
        if ($getUserResponse->status() == 200) {
            if (isset($getUserResponse->object()->Login)) {
                return BigDecimal::of($getUserResponse->object()->Balance);
            }else{
                ValidationException::withMessages([
                    'invalid' => __('Sorry, the funded amount is not valid.')
                ]);
//                return throw ValidationException::withMessages(['invalid' => __("The forex account :login is not exist in MT5!.please choose valid account",['login='>$login])]);
            }
        }
//        return throw ValidationException::withMessages(['invalid' => __("Some thing wrong! Please reload the page and try again!")]);

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
        $url = config('forextrading.mainPasswordChangeUrl');


        $dataArray = array(
            'Login' => $login,

            'MainPassword' => $password,
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
            'Comment' => "agent '553862' - #1431717",
        );
//        dd($dataArray);
        return $this->sendApiRequest($url, $dataArray);

    }

    public function sendApiRequest($URL, $dataArray)
    {
//        dd($URL,$dataArray);
//        $client = new GuzzleHttp\Client();
//        $res = $client->get($URL, $dataArray);
//        echo $res->getStatusCode(); // 200
//        echo $res->getBody(); // { "type": "User", ....
        $response = Http::get($URL, $dataArray);
//        dd($response->body(),$response->json(),$response->object(),$response->collect(),$response->status(),$response->ok(),$response->successful(),$response->failed(),$response->serverError(),$response->clientError(),$response->redirect(),$response->headers());
//
        return $response;

//        $ch = curl_init();
//        $data = http_build_query($dataArray);
//        $getUrl = $URL."?".$data;
//
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//        curl_setopt($ch, CURLOPT_URL, $getUrl);
//        curl_setopt($ch, CURLOPT_TIMEOUT, 80);
//
//        $response=curl_exec ($ch);
////        dd($response);
//        if(curl_error($ch)){
//             return 'Request Error:' . curl_error($ch);
//        }
//        curl_close ($ch);
//        return json_decode($response);
    }
}
