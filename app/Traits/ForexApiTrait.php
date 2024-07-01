<?php

namespace App\Traits;

use App\Enums\ForexAccountStatus;
use App\Models\ForexAccount;
use App\Models\User;
use Brick\Math\BigDecimal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;


trait ForexApiTrait
{
    public function getUserApi($login)
    {
        $getUserUrl = config('forextrading.getUserUrl');
        $dataArray = array(
            'Login' => (int)$login,
        );
//        dd($getUserUrl);
        $response = $this->sendApiRequest($getUserUrl, $dataArray);
//        dd($login,$getUserUrl,$response->object(),$response->status());
        if (isset($response)) {
            if ($response->status() == 200) {
                if ($response->object()->Login != 0) {
                    return $response;
                } else {
//                    ForexAccount::where('login',$login)->update(['status'=>ForexAccountStatus::Archive]);
                    return false;
                }
            }
        } else {
            return false;
        }
    }
    public function getUserInfoUrl($login)
    {
        $getUserUrl = config('forextrading.getUserInfoUrl');

        $dataArray = array(
            'Login' => (int)$login,
        );
//        dd($getUserUrl);
        $response = $this->sendApiRequest($getUserUrl, $dataArray);
//        dd($login,$getUserUrl,$response->object(),$response->status());
        if (isset($response)) {
            if ($response->status() == 200) {
                if ($response->object()->Login != 0) {
                    return $response;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function getUserIBApi($login)
    {
        $getUserUrl = config('forextrading.getUserUrl');

        $dataArray = array(
            'Login' => (int)$login,
        );
//        dd($getUserUrl);
        $response = $this->sendApiRequest($getUserUrl, $dataArray);
//        dd($login,$response->object(),$response->status());
        if (isset($response)) {
            if ($response->status() == 200) {
                if ($response->object()->Login != 0) {
                    return $response;
                } else {
//                    User::where('ib_login',$login)->update(['ib_status'=>ForexAccountStatus::Unknown]);
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function getRoiApi($login)
    {
        $getUserUrl = config('forextrading.getRoiUrl') . '?login=<Login>';

        $dataArray = array(//            'login' => '<'.$login.'>',
        );
//        dd($getUserUrl);
        $response = $this->sendApiRequest($getUserUrl, $dataArray);
//        dd($login,$response->object(),$response->status());
        if (isset($response)) {
            if ($response->status() == 200) {
                if ($response->object()->Login != 0) {
                    return $response;
                } else {
                    //ForexAccount::where('login',$login)->update(['status'=>ForexAccountStatus::Archive]);
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function getMT5GroupList()
    {
        $getUserUrl = config('forextrading.getMT5GroupList');

        $dataArray = array(//            'login' => '<'.$login.'>',
        );
//        dd($getUserUrl);
        $response = $this->sendApiRequest($getUserUrl, $dataArray);
        dd($getUserUrl, $response->object(), $response->status());
        if (isset($response)) {
            if ($response->status() == 200) {
                if ($response->object()->Login != 0) {
                    return $response;
                } else {
                    //ForexAccount::where('login',$login)->update(['status'=>ForexAccountStatus::Archive]);
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function getPositionList($login)
    {
        $getUserUrl = config('forextrading.getPositionList');
        $dataArray = array(
            'Logins' => '1973154',
        );
//        dd($getUserUrl);
        $response = $this->sendApiRequest($getUserUrl, $dataArray);
        dd($login, $response->object(), $response->status());
        if (isset($response)) {
            if ($response->status() == 200) {
                if ($response->object()->Login != 0) {
                    return $response;
                } else {
                    //ForexAccount::where('login',$login)->update(['status'=>ForexAccountStatus::Archive]);
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function getOrderOpenUser($login)
    {
        $getUserUrl = config('forextrading.getOrderOpenUser');
        $dataArray = array(
            'Login' => '1973154',
        );
//        dd($getUserUrl);
        $response = $this->sendApiRequest($getUserUrl, $dataArray);
        dd($login, $response->object(), $response->status());
        if (isset($response)) {
            if ($response->status() == 200) {
                if ($response->object()->Login != 0) {
                    return $response;
                } else {
                    //ForexAccount::where('login',$login)->update(['status'=>ForexAccountStatus::Archive]);
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function getUserAccountBalance($login)
    {
        $getUserUrl = config('forextrading.getUserAccountBalance');
        $dataArray = array(
            'Login' => '6735',
        );
//        dd($getUserUrl);
        $response = $this->sendApiRequest($getUserUrl, $dataArray);
        dd($response->object(), $response->status());
        if (isset($response)) {
            if ($response->status() == 200) {
                if ($response->object()->Login != 0) {
                    return $response;
                } else {
                    //ForexAccount::where('login',$login)->update(['status'=>ForexAccountStatus::Archive]);
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function getDealListUser($login, $start, $end)
    {
        $getUserUrl = config('forextrading.getDealListUser');
        $dataArray = array(
            'Login' => $login,
            'fromDate' => $start,
            'toDate' => $end,
//            'Login' => '9997821',
//            'fromDate' => '1707696000',
//            'toDate' => '1707782399',
        );
//        dd($getUserUrl);
        $response = $this->sendApiRequest($getUserUrl, $dataArray);
//        dd($login,$response->object(),$response->status());
        if (isset($response)) {
            if ($response->status() == 200) {
                return $response;
            }
        } else {
            return false;
        }
    }

    public function getOrderList($login, $start, $end)
    {
        $getUserUrl = config('forextrading.getOrderList');
        $dataArray = array(
            'Login' => $login,
            'fromDate' => $start,
            'toDate' => $end,
//            'Login' => '9997821',
//            'fromDate' => 0,
//            'fromDate' => '1707696000',
//            'toDate' => '1707782399',
        );
//        dd($getUserUrl);
        $response = $this->sendApiRequest($getUserUrl, $dataArray);
//        dd($login,$response->object(),$response->status());
        if (isset($response)) {
            if ($response->status() == 200) {
                return $response;
            }
        } else {
            return false;
        }
    }

    public function getPositionListGroup($group)
    {
        $getUserUrl = config('forextrading.getPositionListGroup');
        $dataArray = array(
            'group' => 'real\Classic\Islamic1',
        );
//        dd($getUserUrl);
        $response = $this->sendApiRequest($getUserUrl, $dataArray);
        dd($response->object(), $response->status());
        if (isset($response)) {
            if ($response->status() == 200) {
                if ($response->object()->Login != 0) {
                    return $response;
                } else {
                    //ForexAccount::where('login',$login)->update(['status'=>ForexAccountStatus::Archive]);
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function getUserInfoApi($login)
    {
        $getUserUrl = config('forextrading.getUserInfoUrl');

        $dataArray = array(
            'Login' => (int)$login,
        );
//        dd($getUserUrl);
        $response = $this->sendApiRequest($getUserUrl, $dataArray);
//        dd($login,$response->object(),$response->status());
        if (isset($response)) {
            if ($response->status() == 200) {
                if ($response->object()->Login != 0) {
                    return $response;
                } else {
                    //ForexAccount::where('login',$login)->update(['status'=>ForexAccountStatus::Archive]);
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function sendApiRequest($URL, $dataArray)
    {
        $clientIp = request()->ip();
//        dd($clientIp);
        if (in_array($clientIp, ['127.0.0.1', '::1'])) {
            $dataArray['URL'] = $URL;
//            $dataArray['Login'] = 88868;
            $localURL = 'https://brokerdemo.brokeret.com/api/get/forex';

//            dd($localURL,$dataArray);
            $response = Http::withoutVerifying()
                ->retry(3, 100)
                ->get($localURL, $dataArray);
//            dd($response->object());
            return $response;

        } else {
            try {
                return Http::retry(3, 100)->get($URL, $dataArray);
            } catch (\GuzzleHttp\Exception\RequestException $exception) {
                return $exception;
            }
        }
    }

    public function sendApiPostRequest($URL, $dataArray)
    {
//        dd('ss');
//        $clientIp = request()->ip();
//        if (in_array($clientIp, ['127.0.0.1', '::1'])) {
//            $dataArray['URL'] = $URL;
//            $localURL = 'https://brokerdemo.brokeret.com/api/post/forex';
////            dd($localURL,$dataArray);
//            return Http::withoutVerifying()
//                ->retry(3, 100)->get($localURL, $dataArray);
//        } else {
            try {
//                dd('ss');
                return Http::retry(3, 100)->post($URL, $dataArray);
            } catch (\GuzzleHttp\Exception\RequestException $exception) {
                return $exception;
            }
//        }
    }

    public function isValidForexAccount($login)
    {
        $getUserUrl = config('forextrading.getUserUrl');

        $dataArray = array(
            'Login' => $login,
        );

        $response = $this->sendApiRequest($getUserUrl, $dataArray);
        if ($response->status() != 200) {
            $message = __('The forex account :login is not exist in MT5! Please choose valid account', ['login' => $login]);
            notify()->error($message, 'Error');
            return false;
        }
        if (!isset($response->object()->Login)) {
            return false;
        }
        if ($response->object()->Login == 0) {
            return false;
        }
        return true;
    }
//
    public function getForexAccountBalance($login)
    {
//        dd($login);
        $getUserResponse = $this->getUserApi($login);
//        dd($getUserResponse);
        if ($getUserResponse) {
            if (isset($getUserResponse->object()->Login)) {
                return BigDecimal::of($getUserResponse->object()->MarginFree)->minus($getUserResponse->object()->Credit);
            } else {
                throw ValidationException::withMessages([
                    'invalid' => __('The forex account :login is not exist in MT5!.please choose valid account', ['login' => $login])
                ]);
                return BigDecimal::of(0);
            }
        }
        return BigDecimal::of(0);
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
        $withdrawResponse = $this->sendApiPostRequest($withdrawUrl, $dataArray);
//        dd($withdrawResponse->object(),$amount);
        if ($withdrawResponse->status() == 200 && $withdrawResponse->object() == 10009) {
            return true;
        } else {
            $message = __('You do not have enough money! Kindly select valid amount', ['login' => $login]);
            notify()->error($message, 'Error');
            return false;
        }
    }

    public function forexDeposit($login, $amount, $comment)
    {
        $url = config('forextrading.depositUrl');

        $dataArray = [
            'Login' => $login,
            'Amount' => $amount,
            'Comment' => $comment,

        ];
        $response = $this->sendApiPostRequest($url, $dataArray);
//        dd($response->object());
        if ($response->status() == 200 && $response->object() == 10009) {
            return true;
        } else {
            $message = __('The forex account :login is not exist in MT5!.please choose valid account', ['login' => $login]);
            notify()->error($message, 'Error');
        }
    }

    public function dealerCreditUrl($login, $amount, $comment)
    {
        $url = config('forextrading.dealerCreditUrl');

        $dataArray = [
            'Login' => 6735,
            'Amount' => 1,
            'Comment' => 'credit balance',

        ];
        $response = $this->sendApiPostRequest($url, $dataArray);
        dd($response->object());
        if ($response->status() == 200 && $response->object() == 10009) {
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
////
//        $dataArray = array(
//            'Login' => $login,
//
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
////
//        $dataArray = array(
//            'Login' => $login,
//
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
    public function updateLeverage($login, $leverage)
    {
        $url = config('forextrading.updateLeverageUrl');

        $dataArray = array(
            'Login' => $login,
            'LeverageAmount' => $leverage,
        );
//        dd($dataArray);
        return $this->sendApiPostRequest($url, $dataArray);

    }

    public function updateAgent($login, $agent)
    {
        $url = config('forextrading.updateAgentAccount');

        $dataArray = array(
            'Login' => $login,
            'Agent' => $agent,
        );
        return $this->sendApiPostRequest($url, $dataArray);

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
        $url = config('forextrading.disableAccountUrl');

        $dataArray = array(
            'Login' => $login,

        );
//        dd($dataArray);
        return $this->sendApiRequest($url, $dataArray);

    }

    public function enableAccount($login)
    {
        $url = config('forextrading.enableAccountUrl');

        $dataArray = array(
            'Login' => $login,

        );
//        dd($dataArray);
        return $this->sendApiRequest($url, $dataArray);

    }
//
//    public function getPageDeal($login, $from, $to)
//    {
//        $url = config('forextrading.pageDealUrl');
////
//        $dataArray = array(
//            'Login' => $login,
//
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
    public function syncForexAccounts($userID = null)
    {
        if (!isset($userID))
            $userID = auth()->user()->id;

        $realAccounts = ForexAccount::where('user_id', $userID)
            ->where('status', ForexAccountStatus::Ongoing)
            ->get();

        $balance = 0;
        foreach ($realAccounts as $account) {
//            dd($account);
            $getUserResponse = $this->getUserApi($account->login);
//            dd($getUserResponse);
//           dd($getUserResponse->object(),$getUserResponse->object()->Login);
            if (!empty($getUserResponse)) {
//                dd($getUserResponse->object(),$getUserResponse->object()->Login);
                if ($getUserResponse->status() == 200 && isset($getUserResponse->object()->Login)) {

                    $this->updateUserAccount($getUserResponse);
                    if ($account->account_type == 'real') {
                        $balance += $getUserResponse->object()->Balance;
                    }
                }
            }
        }
//        dd($balance);
        $this->updateTotalBalance($userID, $balance);

    }

    public function updateUserAccount($getUserResponse, $lastDeposit = false)
    {
        $resData = $getUserResponse->object();
//        dd($resData);
        if (isset($resData->Login)) {
            $forexTrading = ForexAccount::where('login', $resData->Login)->first();
//        $forexTrading->account_name = $resData->Name;
            if ($forexTrading) {
//                $forexTrading->leverage = $resData->Leverage;
//      $forexTrading->email = $resData->Email;
                $forexTrading->balance = $resData->Balance;
                $forexTrading->equity = $resData->Equity;
                $forexTrading->credit = $resData->Credit;
//                $forexTrading->agent = $resData->Agent;
//            $forexTrading->free_margin = $resData->MarginFree;
//            $forexTrading->margin = $resData->Margin;
//                $forexTrading->group = $resData->Group;

                $forexTrading->save();
            }
        }
    }

    public function updateTotalBalance($userID, $balance)
    {
        $user = User::where('id', $userID)->first();
//        $forexTrading->account_name = $resData->Name;
        $user->balance = $balance;
        $user->save();

    }

//    public function syncPricingAccount($login)
//    {
////            dd($account);
//        $getUserResponse = $this->getUserApi($login);
////           dd($getUserResponse->object(),$getUserResponse->object()->Login);
//        if ($getUserResponse->status() == 200 && isset($getUserResponse->object()->Login)) {
//            $this->updatePricingInvestmentAccount($getUserResponse);
//        }
//
//
//    }
//
//    public function updatePricingInvestmentAccount($getUserResponse)
//    {
//        $resData = $getUserResponse->object();
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
////
//        $dataArray = array(
//            'Login' => $login,
//
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
