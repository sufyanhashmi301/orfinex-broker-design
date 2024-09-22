<?php

namespace Payment\Bridgerpay;

use App\Enums\TxnStatus;
use App\Models\User;
//use charlesassets\LaravelPerfectMoney\PerfectMoney;
use GuzzleHttp\Client;
use Payment\Transaction\BaseTxn;
use Txn;

class BridgerpayTxn extends BaseTxn
{
    /**
     * @var mixed|string
     */
    private mixed $sendTo;

    public function __construct($txnInfo)
    {
        parent::__construct($txnInfo);
        $credentials = gateway_info('bridgerpay');
//        dd($credentials,$credentials->API_LOCATION);
        $this->redirectUrl = $credentials->REDIRECT_URL ?? '';

        $this->baseUrl = $credentials->API_LOCATION ?? 'https://api.bridgerpay.com';
        $this->apiKey = $credentials->API_KEY;
        $this->cashierKey = $credentials->CASHIER_KEY;
        $this->userName = $credentials->USERNAME;
        $this->password = $credentials->PASSWORD;
        $this->client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
            ],
        ]);
    }
    public function deposit()
    {

        $token = $this->authenticate();

        $response = $this->client->request('POST', $this->baseUrl.'/v2/cashier/session/create/' . $this->apiKey, [
            'body' => json_encode([
                'cashier_key' => $this->cashierKey,
                'order_id' => the_hash($this->txn),
                'currency' => $this->currency,
                'country' => $this->userCountryCode,
                'first_name' => $this->firstName,
                'last_name' =>  $this->lastName,
                'email' => $this->userEmail,
                'language' => 'en',
                'state' => null,
                'address' => $this->userAddress,
                'city' =>  $this->userCity,
                'zip_code' => $this->userAddress,
                'theme' => 'bright',
                'amount' =>  $this->amount,
                'phone' => $this->userPhone,
                'paywith_max_instances_limit' => 3
            ]),
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Host' => 'api.bridgerpay.com',
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        $cashierKey =  $this->cashierKey;
        $cashierToken =  $data['result']['cashier_token'];

        return view('gateway.bridgerpay',compact('cashierKey', 'cashierToken'));
    }
    public function authenticate()
    {
        $response = $this->client->request('POST', $this->baseUrl.'/v2/auth/login', [
            'body' => json_encode([
                'user_name' => $this->userName,
                'password' => $this->password,
            ]),
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
//        dd($data);
        return $data['result']['access_token']['token'];

    }
//    public function deposit()
//    {
//        $paymentUrl = route('ipn.bridgerPay');
//        $noPaymentUrl = route('status.cancel');
//        return 'deposit';
//
////        return BridgerPay::render(['PAYMENT_AMOUNT' => $this->amount, 'PAYMENT_ID' => $this->txn, 'PAYMENT_URL' => $paymentUrl, 'PAYMENT_UNITS' => $this->currency, 'NOPAYMENT_URL' => $noPaymentUrl, 'NOPAYMENT_URL_METHOD' => 'GET']);
//    }

//    public function withdraw()
//    {
//
//        $pm = new BridgerPay;
//        $sendMoney = $pm->getBalance($this->amount, $this->sendTo);
////dd($sendMoney,$this->amount,$this->sendTo);
//        if($sendMoney['status'] == 'success')
//        {
//            Txn::update($this->txn, TxnStatus::Success, $this->userId);
//        }
//
//        if($sendMoney['status'] == 'error')
//        {
//            $user = User::find($this->userId);
//            $user->increment('balance', $this->final_amount);
//            Txn::update($this->txn, TxnStatus::Failed, $this->userId);
//        }
//
//        return true;
//    }
}
