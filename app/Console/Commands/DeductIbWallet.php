<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Account;
use App\Models\Ledger;
use Brick\Math\BigDecimal;

class DeductIbWallet extends Command
{
    protected $signature = 'wallet:deduct-ib';
    protected $description = 'Deduct rounded amounts from users\' IB wallets and update their last ledger entries';

    public function handle()
    {
        $deductions = [
            ['user_id' => 39, 'email' => 'bksanjana800@gmail.com', 'amount' => '47.96'],
            ['user_id' => 40, 'email' => 'm9024143615@gmail.com', 'amount' => '36.84'],
            ['user_id' => 52, 'email' => 'narbadamahiya396@gmail.com', 'amount' => '15.03'],
            ['user_id' => 63, 'email' => 'Krishanmaina50@gmail.com', 'amount' => '38.65'],
            ['user_id' => 64, 'email' => 'stilloutoffmind@gmail.com', 'amount' => '12.21'],
            ['user_id' => 68, 'email' => 'alokmuwal123@gmail.com', 'amount' => '27.01'],
            ['user_id' => 84, 'email' => 'sanjay.averindia@gmail.com', 'amount' => '15.66'],
            ['user_id' => 115, 'email' => 'AJAY26607@GMAIL.COM', 'amount' => '47.89'],
            ['user_id' => 135, 'email' => 'sangeetajakhar16@gmail.com', 'amount' => '335.18'],
            ['user_id' => 144, 'email' => 'banwari.sikar@gmail.com', 'amount' => '25.16'],
            ['user_id' => 157, 'email' => 'sangeetajakhar36@gmail.com', 'amount' => '2.64'],
            ['user_id' => 160, 'email' => 'sureshdh729@gmail.com', 'amount' => '70.31'],
            ['user_id' => 173, 'email' => 'vijaybagaria17@gmail.com', 'amount' => '306.77'],
            ['user_id' => 179, 'email' => 'nishay23782@gmail.com', 'amount' => '288.18'],
            ['user_id' => 195, 'email' => 'bhuneshoareek01@gmail.com', 'amount' => '82.29'],
            ['user_id' => 292, 'email' => 'bhamumukesh20@gmail.com', 'amount' => '11.00'],
            ['user_id' => 301, 'email' => 'choudharyneymar07071997@gmail.com', 'amount' => '559.39'],
            ['user_id' => 305, 'email' => 'vinodpareek09@gmail.com', 'amount' => '62.67'],
            ['user_id' => 317, 'email' => 'bhaskarmamita1973@gmail.com', 'amount' => '40.76'],
            ['user_id' => 322, 'email' => 'sandeepsewda94@gmail.com', 'amount' => '15.03'],
            ['user_id' => 323, 'email' => 'umashankar6722@gmail.com', 'amount' => '240.98'],
            ['user_id' => 340, 'email' => 'kkankita143@gmail.com', 'amount' => '15.39'],
            ['user_id' => 350, 'email' => 'rajni.averindia@gmail.com', 'amount' => '12.25'],
            ['user_id' => 358, 'email' => 'babulalyadav200@gmail.com', 'amount' => '8.08'],
            ['user_id' => 367, 'email' => 'digitalamitt99@gmail.com', 'amount' => '8.59'],
            ['user_id' => 373, 'email' => 'kanarammeena447@gmail.com', 'amount' => '7.43'],
            ['user_id' => 375, 'email' => 'sunilyadav10349@gmail.com', 'amount' => '3.55'],
            ['user_id' => 384, 'email' => 'cbsain.sikar@gmail.com', 'amount' => '24.28'],
            ['user_id' => 393, 'email' => 'tikyani.prakash@gmail.com', 'amount' => '3.68'],
            ['user_id' => 399, 'email' => 'shaitanshekhawat628@gmail.com', 'amount' => '4.83'],
            ['user_id' => 406, 'email' => 'afsinkhan66@gmail.com', 'amount' => '4.44'],
            ['user_id' => 413, 'email' => 'lokeshagrawal20005@gmail.com', 'amount' => '5.84'],
            ['user_id' => 422, 'email' => 'divanshuyadav078@gmail.com', 'amount' => '16.71'],
            ['user_id' => 436, 'email' => 'priyayadav8819@gmail.com', 'amount' => '2.20'],
            ['user_id' => 465, 'email' => 'vinodkumawat408888@gmail.com', 'amount' => '8.54'],
            ['user_id' => 466, 'email' => 'abdulzaid981@gmail.com', 'amount' => '108.47'],
            ['user_id' => 504, 'email' => 'aditya.purohit990@gmail.com', 'amount' => '15.16'],
            ['user_id' => 509, 'email' => 'dia.y27810@gmail.com', 'amount' => '11.64'],
            ['user_id' => 524, 'email' => 'nkumar050298@gmail.com', 'amount' => '87.49'],
            ['user_id' => 540, 'email' => 'kumar8233sanjay@gmail.com', 'amount' => '19.15'],
            ['user_id' => 574, 'email' => 'sunitayadav84321@gmail.com', 'amount' => '14.51'],
            ['user_id' => 578, 'email' => 'aditichaudhary.55555@gmail.com', 'amount' => '1227.49'],
            ['user_id' => 582, 'email' => 'nehagupta1097@gmail.com', 'amount' => '115.83'],
            ['user_id' => 583, 'email' => 'mohit0j7@gmail.com', 'amount' => '9.68'],
            ['user_id' => 592, 'email' => 'ys646057@gmail.com', 'amount' => '27.45'],
            ['user_id' => 600, 'email' => 'rakeshbhartiyasikar@gmail.com', 'amount' => '75.49'],
            ['user_id' => 606, 'email' => 'ajaykajla7118@gmail.com', 'amount' => '48.34'],
            ['user_id' => 608, 'email' => 'laweshs8@gmail.com', 'amount' => '57.89'],
            ['user_id' => 618, 'email' => 'mlmsunil24@gmail.com', 'amount' => '21.89'],
            ['user_id' => 626, 'email' => 'kantrk123@gmail.com', 'amount' => '18.48'],
            ['user_id' => 631, 'email' => 'mk7891340945@gmail.com', 'amount' => '10.66'],
            ['user_id' => 686, 'email' => 'mk4863791@gmail.com', 'amount' => '5.96'],
            ['user_id' => 688, 'email' => 'rakeshdth60@gmail.com', 'amount' => '159.74'],
            ['user_id' => 689, 'email' => 'dilipprajapat743@gmail.com', 'amount' => '23.00'],
            ['user_id' => 722, 'email' => 'royalrks1998@gmail.com', 'amount' => '8.21'],
            ['user_id' => 735, 'email' => 'ankitjerthi3330@gmail.com', 'amount' => '99.17'],
            ['user_id' => 743, 'email' => 'ravindra8112a@gmail.com', 'amount' => '58.99'],
            ['user_id' => 746, 'email' => 'mukeshkumarsunda88@gmail.com', 'amount' => '11.80'],
            ['user_id' => 1281, 'email' => 'kumarimamta55492@gmail.com', 'amount' => '8.84'],
            ['user_id' => 1291, 'email' => 'skmksunda@gmail.com', 'amount' => '162.40'],
            ['user_id' => 1292, 'email' => 'sanjayyadav18378@gmail.com', 'amount' => '1.72'],
            ['user_id' => 1293, 'email' => 'goritanan123@gmail.com', 'amount' => '81.45'],
            ['user_id' => 1315, 'email' => 'vivek5479@gmail.com', 'amount' => '38.68'],
            ['user_id' => 1347, 'email' => 'virendrajatkairu8005699001@gmail.com', 'amount' => '466.52'],
            ['user_id' => 1506, 'email' => 'addharmendra@gmail.com', 'amount' => '4.96'],
            ['user_id' => 2165, 'email' => 'gopalbhati793@gmail.com', 'amount' => '6.66'],
            ['user_id' => 2412, 'email' => 'shubhamkumar10992@gmail.com', 'amount' => '2.22'],
            ['user_id' => 2453, 'email' => 'Chikusangeeta23@gmail.com', 'amount' => '285.00'],
            ['user_id' => 2464, 'email' => 'lucky.dogmo123@gmail.com', 'amount' => '2.22'],
            ['user_id' => 2543, 'email' => 'anilkyadav171984@gmail.com', 'amount' => '5.33'],
            ['user_id' => 2612, 'email' => 'aditifx555@gmail.com', 'amount' => '690.00'],
        ];


        $insufficient = [];

        DB::beginTransaction();
        try {
            foreach ($deductions as $item) {
                $userId = $item['user_id'];
                $email = $item['email'];
                $toDeduct = BigDecimal::of($item['amount']);

                $account = Account::where('user_id', $userId)
                    ->where('balance', 'ib_wallet')
                    ->lockForUpdate()
                    ->first();

                if (!$account) {
                    $insufficient[] = "User {$userId} ({$email}): IB wallet not found";
                    continue;
                }

                $currentAmount = BigDecimal::of($account->amount);
                $deductAmt = $toDeduct;

                if ($currentAmount->compareTo($toDeduct) < 0) {
                    $deductAmt = $currentAmount;
                    $remaining = $toDeduct->minus($currentAmount);
                    $insufficient[] = "User {$userId} ({$email}): remaining to subtract {$remaining}";
                }

                $newAmount = $currentAmount->minus($deductAmt);
                $account->amount = (string)$newAmount;
                $account->save();

                $lastLedger = Ledger::where('account_id', $account->id)
                    ->orderBy('created_at', 'desc')
                    ->lockForUpdate()
                    ->first();

                if ($lastLedger) {
                    $newLedgerBal = BigDecimal::of($lastLedger->balance)->minus($deductAmt);
                    $lastLedger->balance = (string)$newLedgerBal;
                    $lastLedger->save();
                } else {
                    Ledger::create([
                        'transaction_id' => null,
                        'account_id' => $account->id,
                        'debit' => (string)$deductAmt,
                        'credit' => null,
                        'balance' => (string)$newAmount,
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error during deductions: ' . $e->getMessage());
            return 1;
        }

        if (!empty($insufficient)) {
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
