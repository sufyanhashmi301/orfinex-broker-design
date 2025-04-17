<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Account;
use App\Models\Ledger;
use Brick\Math\BigDecimal;

class DeductIbWallet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallet:deduct-ib';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deduct specified amounts from users\' IB wallets and update the last ledger entry balance; if insufficient funds, deduct available and report remainder';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $deductions = [
            ['user_id' => 394,  'email' => 'pks8239068425@gmail.com',             'amount' => '89.98'],
            ['user_id' => 399,  'email' => 'shaitanshekhawat628@gmail.com',       'amount' => '3.29'],
            ['user_id' => 466,  'email' => 'abdulzaid981@gmail.com',              'amount' => '18.74'],
            ['user_id' => 1347, 'email' => 'virendrajatkairu8005699001@gmail.com','amount' => '3.77'],
            ['user_id' => 350,  'email' => 'rajni.averindia@gmail.com',           'amount' => '5.41'],
            ['user_id' => 84,   'email' => 'sanjay.averindia@gmail.com',          'amount' => '5.19'],
            ['user_id' => 373,  'email' => 'kanarammeena447@gmail.com',           'amount' => '2.15'],
            ['user_id' => 156,  'email' => 'junedkhn30@gmail.com',                'amount' => '0.52'],
            ['user_id' => 126,  'email' => 'jinisha90196@gmail.com',              'amount' => '0.16'],
            ['user_id' => 1292, 'email' => 'sanjayyadav18378@gmail.com',          'amount' => '0.16'],
            ['user_id' => 1278, 'email' => 'officialreenakala@gmail.com',         'amount' => '0.84'],
        ];

        $insufficient = [];

        DB::beginTransaction();

        try {
            foreach ($deductions as $item) {
                $userId    = $item['user_id'];
                $email     = $item['email'];
                $toDeduct  = BigDecimal::of($item['amount']);

                $account = Account::where('user_id', $userId)
                    ->where('balance', 'ib_wallet')
                    ->lockForUpdate()
                    ->first();

                if (! $account) {
                    $insufficient[] = "User {$userId} ({$email}): IB wallet not found";
                    continue;
                }

                $currentAmount = BigDecimal::of($account->amount);
                $deductAmt     = $toDeduct;

                if ($currentAmount->compareTo($toDeduct) < 0) {
                    // Not enough: we'll deduct all available and report remaining
                    $deductAmt    = $currentAmount;
                    $remaining    = $toDeduct->minus($currentAmount);
                    $insufficient[] = "User {$userId} ({$email}): remaining to subtract {$remaining}";
                }

                // Deduct available (or full) amount
                $newAmount = $currentAmount->minus($deductAmt);
                $account->amount = (string) $newAmount;
                $account->save();

                // Update last ledger entry or create if none
                $lastLedger = Ledger::where('account_id', $account->id)
                    ->orderBy('created_at', 'desc')
                    ->lockForUpdate()
                    ->first();

                if ($lastLedger) {
                    $prevBalance   = BigDecimal::of($lastLedger->balance);
                    $newLedgerBal  = $prevBalance->minus($deductAmt);
                    $lastLedger->balance = (string) $newLedgerBal;
                    $lastLedger->save();
                } else {
                    $ledgerBalance = $newAmount;
                    Ledger::create([
                        'transaction_id' => null,
                        'account_id'     => $account->id,
                        'debit'          => (string) $deductAmt,
                        'credit'         => null,
                        'balance'        => (string) $ledgerBalance,
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error during deductions: ' . $e->getMessage());
            return 1;
        }

        if (! empty($insufficient)) {
            $this->info("Users with insufficient balance:");
            foreach ($insufficient as $line) {
                $this->line($line);
            }
        } else {
            $this->info('All deductions applied successfully.');
        }

        return 0;
    }
}
