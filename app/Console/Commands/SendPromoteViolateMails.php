<?php

namespace App\Console\Commands;

use App\Enums\AccountTypePhase as AccountTypePhaseEnum;
use App\Traits\NotifyTrait;
use App\Enums\InvestmentStatus;
use App\Enums\TradingObjective;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\AccountTypeInvestment;

class SendPromoteViolateMails extends Command
{

    use NotifyTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:promote-violate-accounts-mails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will run every 5 minutes to check if any accounts have been recently promoted or violated. If yes then send mails accordingly.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $accounts = AccountTypeInvestment::whereIn('status', [InvestmentStatus::VIOLATED, InvestmentStatus::PASSED, InvestmentStatus::ACTIVE])->where('mail_sent', 0)->get();

        foreach ($accounts as $account) {

            $account_phase = $account->getPhaseSnapshotData();

            if(!$account_phase || !$account || !$account->user) {
                continue;
            }

            $shortcodes = [
                '[[full_name]]' => $account->user->first_name . ' ' . $account->user->last_name,
                '[[site_title]]' => setting('site_title', 'global'),
                '[[phase_step]]' => $account_phase['type'] == AccountTypePhaseEnum::EVALUATION ? 'Evaluation' : 'Verification'
            ];

            // Violation Mail
            if($account->status == InvestmentStatus::VIOLATED) {
                $shortcodes['[[violation_reason]]'] = $account->violation_reason == TradingObjective::DD_VIOLATED ? 'Daily Drawdown Limit Over' : 'Max. Drawdown Limit Over';
                $mail = $this->mailNotify($account->user->email, 'phase_violation', $shortcodes);
            }   

            // Promotion Mail
            if($account->status == InvestmentStatus::PASSED) {
                $mail = $this->mailNotify($account->user->email, 'phase_promotion', $shortcodes);
            }

            // New Account Mail
            if($account->status == InvestmentStatus::ACTIVE) {
                $shortcodes2 = [
                    '[[full_name]]' => $account->user->first_name . ' ' . $account->user->last_name,
                    '[[account_login]]' => $account->login,
                    '[[email]]' => $account->user->email,
                    '[[account_password]]' => $account->trader_type == \App\Enums\TraderType::MT5 ? $account->main_password : $account->user->plaformAccountCredentials->password, 
                    '[[server]]' => $account->trader_type == \App\Enums\TraderType::MT5 ? setting('live_server', 'platform_api') : setting('mt_live_server_real', 'match_trader_platform_api'),
                    '[[phase_step]]' => $account_phase['type'] == AccountTypePhaseEnum::EVALUATION ? 'Evaluation' : 'Verification',
                    '[[site_title]]' => setting('site_title', 'global'),
                ];

                if($account->login == null) {
                    // in case if the login is not created
                    $mail = false;

                } elseif($account_phase['type'] != AccountTypePhaseEnum::FUNDED) {
                    $mail = $this->mailNotify($account->user->email, 'new_account_details', $shortcodes2);
                    
                } else {
                    $mail = $this->mailNotify($account->user->email, 'contract_pending', $shortcodes2);
                }
                
            }

            // If mail is successful set the mail_sent status to 1
            if($mail) {
                $account->mail_sent = 1;
                $account->save();
            }

        }

        $this->info('Mails have been sent!');
        return Command::SUCCESS;
    }
}
