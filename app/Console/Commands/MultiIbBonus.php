<?php

namespace App\Console\Commands;

use App\Models\IbSchema;
use App\Models\IbTransaction;
use App\Models\LevelReferral;
use Carbon\Carbon;
use Illuminate\Console\Command;
use PhpParser\Node\Stmt\Foreach_;

class MultiIbBonus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multiIB:Bonus';

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
        //level referral
//        if (setting('site_referral', 'global') == 'level' && setting('investment_level')) {
        $ibSchema = IbSchema::where('type','multi_ib')->where('status',true)->first();
        if(!$ibSchema){
            return false;
        }

        $IBTransactions = IbTransaction::whereNull('clear_at')->get();
        Foreach($IBTransactions as $IBTransaction){
//            dd($IBTransaction->user);
            $level = LevelReferral::where('type', 'multi_ib')->max('the_order') + 1;
            creditMultiIbBonus($IBTransaction->user, 'multi_ib', $IBTransaction->profit, $level);
            $IBTransaction->clear_at = Carbon::now();
            $IBTransaction->save();
        }
        return Command::SUCCESS;
    }
}
