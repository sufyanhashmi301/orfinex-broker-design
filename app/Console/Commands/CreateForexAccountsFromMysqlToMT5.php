<?php

namespace App\Console\Commands;

use App\Traits\ForexApiTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CreateForexAccountsFromMysqlToMT5 extends Command
{
    protected $signature = 'forex:create-accounts-from-mysql-to-mt5';
    protected $description = 'Create forex accounts from mt5_users table in orfinex_new database';
    use ForexApiTrait;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $accounts = DB::connection('mt5_db')
            ->table('mt5_users')
            ->where('Group', 'like', '%SolidEdge%')
            ->where('CertSerialNumber', 0)
            //->where('Login', '9997218')
            //->take(1)
            //->orderBy('Login','desc')
            ->get();


        foreach ($accounts as $account) {
//            dd($account);
            $data = [
                'Login' => $account->Login,
//                'Login' => 920006,
                'Name' => $account->FirstName.' '.$account->LastName,
//                'Name' => 'test-sufyan',
                'Leverage' => $account->Leverage,
                'Group' => $account->Group,
//                'Group' => 'Unofficial\1',
                'Email' => $account->Email,
//                'Email' => 'sufyan@gmail.com',
                'Phone' => $account->Phone,
                'City' => $account->City,
                'State' => $account->State,
                'Address' => $account->Address,
                'ZipCode' => $account->ZipCode,
                'Country' => $account->Country,
                'Agent' => $account->Agent,
                'Comment' => $account->Comment,
                'LeadCampaign' => $account->LeadCampaign,
                'LeadSource' => $account->LeadSource,
                'ID' => $account->ID,
                'Language' => $account->Language,
                'Rights' => $account->Rights,
//                'Rights' => 'USER_RIGHT_ALL',
                'Status' => 'YES',
                'MasterPassword' => 'SNNH@2024@bol',
                'InvestorPassword' => 'SNNH@2024@bol',
            ];

            $digit = 88;
            $data['Login'] = $digit . $account->Login;
//            dd($data);
            $URL = config('forextrading.createUserUrl');
            $response = $this->sendApiPostRequest($URL, $data);
//            dd($response->object(),$data);

            if ($response->status() == 200 && $response->successful() && $response->json('ResponseCode') == 0) {
//                $this->info("Account created for user: {$account->full_name}");
                $resData = $response->object();
                $targetId = $resData->Login;
                $amount = $account->Balance;
                //deposit
               $this->deposit($targetId,$amount);

                $credit = $account->Credit;
                //credit
                $this->credit($targetId,$credit);

                //update status
                $this->update($account->Login);

                echo "created successfully login: {$targetId}"."\n";

            }
            elseif ($response->status() == 200 && $response->successful() && $response->json('ResponseCode') == 3004)   {
                    echo "already exist: {$data['Login']}"."\n";
            }
            elseif ($response->status() == 200 && $response->successful() && $response->json('ResponseCode') == 3003)   {
                echo "The login is reserved on another server: {$data['Login']}"."\n";
            }
            else{
                echo "Failed to create account for user: {$data['Login']} due to {$response->json('ResponseCode')}". "\n";
            }
        }
    }

    public function deposit($targetId,$amount){
        $comment = 'deposit/from/mysql';
        $depositResponse = $this->forexDeposit($targetId,$amount,$comment);
        if($depositResponse){
//            echo "Deposited successfully in login: {$targetId}"."\n";

        } else {
            echo "Deposited failed in login: {$targetId}, amount: {$amount}"."\n";

        }
    }
    public function credit($targetId,$credit){
        $comment = 'credit/from/mysql';
        $depositResponse = $this->dealerCreditUrl($targetId,$credit,$comment);
        if($depositResponse){
//            echo "credited successfully in login: {$targetId}"."\n";
        } else {
            echo "credited failed in login: {$targetId}, credit: {$credit}"."\n";

        }
    }

    public function update($targetId){
        $accounts = DB::connection('mt5_db')
            ->table('mt5_users')
            ->where('Login', $targetId)
            ->update(['CertSerialNumber'=>1]);
    }
}
