<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Enums\InvestmentStatus;
use Illuminate\Console\Command;
use App\Models\AccountTypeInvestment;
use Illuminate\Support\Facades\Artisan;

class RecentApprovedAccountsUpdateStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:recent-approved-accounts-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is use to update the stats if there are one or more accounts that have been approved in the past 5 minutes.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $recent_active_investments = AccountTypeInvestment::where('status', InvestmentStatus::ACTIVE)
                                        ->where('created_at', '>=', Carbon::now()->subMinutes(5))
                                        ->get();

        if(count($recent_active_investments) > 0) {
            Artisan::call('update:investment-stats --both');
        }
    }
}
