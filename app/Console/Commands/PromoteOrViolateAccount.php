<?php

namespace App\Console\Commands;

use App\Enums\InvestmentStatus;
use App\Models\AccountTypeInvestment;
use App\Services\AccountTypeInvestmentService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class PromoteOrViolateAccount extends Command
{
    protected $investment_service;

    public function __construct(AccountTypeInvestmentService $investment_service) {
        parent::__construct();
        $this->investment_service = $investment_service;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounts:promote-or-violate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will run every hours after stats hourly records to check existing users for promotion or violation.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // check the stats of all the users to see if they require promotion or violation

        // get all the accounts 
        $all_accounts = AccountTypeInvestment::all();

        // delete all accounts that do not have login id assigned and the status is set as active
        foreach($all_accounts as $account) {
            if($account->login == null && $account->status == InvestmentStatus::ACTIVE) {
                $account->accountTypeInvestmentSnapshot()->delete();
                $account->accountTypeInvestmentPhaseApproval()->delete();
                $account->delete();
            }
        }        

        // check the stats of all accounts
        foreach( $all_accounts as $account ) {
            $this->investment_service->tradingStats($account->id);
        }

        $this->info('Request Completed!');

        // send emails to promoted and violated accounts accordingly
        // Artisan::call('send:promote-violate-accounts-mails');


    }
}
