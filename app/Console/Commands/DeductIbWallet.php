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
            ['user_id' => 606,  'email' => 'ajaykajla7118@gmail.com',              'amount' => '610.1'],
            ['user_id' => 421,  'email' => 'rajshabh2@gmail.com',                  'amount' => '22.83785'],
            ['user_id' => 592,  'email' => 'ys646057@gmail.com',                  'amount' => '35.465'],
            ['user_id' => 305,  'email' => 'vinodpareek09@gmail.com',             'amount' => '31.382'],
            ['user_id' => 272,  'email' => 'rekhasongara751@gmail.com',           'amount' => '4.1802'],
            ['user_id' => 7,    'email' => 'rakeshdth40@gmail.com',               'amount' => '10.135'],
            ['user_id' => 1293, 'email' => 'goritanan123@gmail.com',              'amount' => '18.719999999996'],
            ['user_id' => 465,  'email' => 'vinodkumawat408888@gmail.com',        'amount' => '1.83295'],
            ['user_id' => 394,  'email' => 'pks8239068425@gmail.com',             'amount' => '99.762350000001'],
            ['user_id' => 399,  'email' => 'shaitanshekhawat628@gmail.com',       'amount' => '12.50715'],
            ['user_id' => 323,  'email' => 'umashankar6722@gmail.com',            'amount' => '72.076100000001'],
            ['user_id' => 466,  'email' => 'abdulzaid981@gmail.com',              'amount' => '54.0125'],
            ['user_id' => 317,  'email' => 'bhaskarmamita1973@gmail.com',         'amount' => '4.8979999999999'],
            ['user_id' => 135,  'email' => 'sangeetajakhar16@gmail.com',          'amount' => '146.71055'],
            ['user_id' => 578,  'email' => 'aditichaudhary.55555@gmail.com',      'amount' => '41.264'],
            ['user_id' => 64,   'email' => 'stilloutoffmind@gmail.com',           'amount' => '8.64735'],
            ['user_id' => 1347,'email' => 'virendrajatkairu8005699001@gmail.com','amount' => '72.35'],
            ['user_id' => 1236,'email' => 'umapy1985@gmail.com',                 'amount' => '170.8144'],
            ['user_id' => 129, 'email' => 'harikrishanrahar@gmail.com',          'amount' => '1749.84'],
            ['user_id' => 47,  'email' => 'priyankafandan96@gmail.com',          'amount' => '990.84'],
            ['user_id' => 36,  'email' => 'neharahar9009@gmail.com',             'amount' => '231.77'],
            ['user_id' => 1307,'email' => 'garyrao.1999@gmail.com',              'amount' => '2.94'],
            ['user_id' => 626, 'email' => 'kantrk123@gmail.com',                 'amount' => '22.43'],
            ['user_id' => 350, 'email' => 'rajni.averindia@gmail.com',           'amount' => '16.2713'],
            ['user_id' => 524, 'email' => 'nkumar050298@gmail.com',              'amount' => '16.72635'],
            ['user_id' => 84,  'email' => 'sanjay.averindia@gmail.com',          'amount' => '16.88'],
            ['user_id' => 373, 'email' => 'kanarammeena447@gmail.com',           'amount' => '7.9888'],
            ['user_id' => 156, 'email' => 'junedkhn30@gmail.com',                'amount' => '1.68'],
            ['user_id' => 126, 'email' => 'jinisha90196@gmail.com',              'amount' => '0.52'],
            ['user_id' => 1292,'email' => 'sanjayyadav18378@gmail.com',          'amount' => '0.52'],
            ['user_id' => 1278,'email' => 'officialreenakala@gmail.com',         'amount' => '2.72'],
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
