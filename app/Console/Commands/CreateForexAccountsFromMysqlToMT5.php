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
//            ->where('Group', 'real\Classic\Swap1')
            ->where('Login', '9997218')
            ->get();

        foreach ($accounts as $account) {
//            dd($account);
            $data = [
                'Name' => $account->FirstName.' '.$account->LastName,
                'Name' => 'test-sufyan',
                'Leverage' => $account->Leverage,
                'Group' => $account->Group,
                'Group' => 'real\Classic\Swap1',
                'Email' => $account->Email,
                'Email' => 'sufyan@gmail.com',
                'Phone' => $account->Phone,
                'City' => $account->City,
                'State' => $account->State,
                'Address' => $account->Address,
                'ZipCode' => $account->ZipCode,
                'Country' => $account->Country,
                'Login' => 920006,
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
//            dd($data);

            $URL = config('forextrading.createUserUrl');
            $response = $this->sendApiPostRequest($URL, $data);
//            dd($response->object());

            if ($response->status() == 200 && $response->successful() && $response->json('ResponseCode') == 0) {
//                $this->info("Account created for user: {$account->full_name}");
                $resData = $response->object();
                $targetId = $resData->Login;
                $comment = 'deposit/from/mysql';
                $depositResponse = $this->forexDeposit($targetId, $account->Balance,$comment);
                if($depositResponse){
                    echo "Deposited successfully in login: {$targetId}"."\n";

                } else {
                    echo "Deposited failed in login: {$targetId}, amount: {$account->Balance}"."\n";

                }
            }
            elseif ($response->status() == 200 && $response->successful() && $response->json('ResponseCode') == 3004)   {

                $data['Login'] = 1 . 920006;
                $response = $this->sendApiPostRequest($URL, $data);
                if ($response->status() == 200 && $response->successful() && $response->json('ResponseCode') == 0) {
                    echo "Prefixed,  Email: {$account->Email}, login: {$account->Login}"."\n";
                } else {
                    echo "Failed to create account for user: {$account->Login}"."\n";
                }
            }
        }
    }
}
