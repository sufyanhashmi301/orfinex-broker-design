<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Models\User;
use App\Models\WalletBalance;
use App\Models\Transaction;
use App\Models\Ledger;
use App\Services\WalletService;
use App\Enums\AccountBalanceType;
use App\Enums\TxnStatus;
use App\Enums\TxnTargetType;
use App\Enums\TxnType;
use Brick\Math\BigDecimal;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Txn;

class UpdateWalletBalance extends Command
{
    protected $signature = 'wallet:update-balance';
    protected $description = 'Update user wallet balances based on WalletBalance table and record transactions';

    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        parent::__construct();
        $this->walletService = $walletService;
    }

    public function handle()
    {
        $walletBalances = WalletBalance::all();
//        dd($walletBalances);

        foreach ($walletBalances as $walletBalance) {
            try {
                $email = $walletBalance->email;
                $user = User::where('email', $email)->first();

                if (!$user) {
                    Log::warning("No user found with email: {$email}");
                    $this->info("No user found with email: {$email}");
                    continue;
                }

                $wallet = get_user_account($user->id, AccountBalanceType::IB_WALLET);
//                dd($wallet);

                if (!$wallet) {
                    Log::warning("No IB Wallet found for user: {$email}");
                    continue;
                }

                $amount = (float)$walletBalance->balance;
                $currentBalance = $this->walletService->getLedgerBalance($wallet->id);
//                if ($amount->isGreaterThan(0)) {
                    // Create transaction entry
                    $transaction = Txn::new(
                        $amount, 0, $amount, 'system',
                        "Migrated Old system balance",
                        TxnType::IbBonus, TxnStatus::Success, base_currency(),
                        $amount, $user->id, null, 'User', [],
                        "Migrated Old system balance", $wallet->wallet_id, TxnTargetType::Wallet->value
                    );

                    // Add balance to wallet
                    $this->addBalance($transaction);
//                }

                Log::info("Wallet balance updated and transaction recorded for user: {$email}");
            } catch (\Exception $e) {
                Log::error("Error updating wallet balance for user: {$email} - " . $e->getMessage());
            }
        }
    }

    protected function addBalance($transaction)
    {
        $userAccount = get_user_account_by_wallet_id($transaction->target_id);
//        dd($userAccount);

        $wallet = new WalletService();
        $ledgerBalance = $wallet->getLedgerBalance($userAccount->id);

        $wallet->createCreditLedgerEntry($transaction, $ledgerBalance);
//        dd($userAccount);


        $userAccount->amount = BigDecimal::of($userAccount->amount)->plus(BigDecimal::of($transaction->amount));
        $userAccount->save();
//        dd('s');

    }
}

// Command to execute this script
// Run the following in the terminal:
// php artisan wallet:update-balance
