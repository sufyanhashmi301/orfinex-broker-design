<?php

namespace App\Console\Commands;

use App\Models\ForexAccount;
use App\Traits\ForexApiTrait;
use Illuminate\Console\Command;

class UpdateForexBalance extends Command
{
    use ForexApiTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:balance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $forexAccounts = ForexAccount::where('account_type','real')->where('status','Ongoing')->get();
        foreach ($forexAccounts as $account){
            $getUserResponse = $this->getUserApi($account->login);
//            dd($getUserResponse);
//           dd($getUserResponse->object(),$getUserResponse->object()->Login);
            if (!empty($getUserResponse)) {
//                dd($getUserResponse->object(),$getUserResponse->object()->Login);
                if ($getUserResponse->status() == 200 && isset($getUserResponse->object()->Login)) {
                    $this->updateUserAccount($getUserResponse);
                }
            }
        }
    }
    public function updateUserAccount($getUserResponse, $lastDeposit = false)
    {
        $resData = $getUserResponse->object();

        if (isset($resData->Login)) {
            $forexTrading = ForexAccount::where('login', $resData->Login)->first();
            if ($forexTrading) {
                $forexTrading->balance = $resData->Balance;
                $forexTrading->equity = $resData->Equity;
                $forexTrading->credit = $resData->Credit;
                $forexTrading->save();
            }
        }
    }

}
