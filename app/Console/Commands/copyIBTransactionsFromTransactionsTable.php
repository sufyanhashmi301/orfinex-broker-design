<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\IBTransaction;
use App\Services\IBTransactionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class copyIBTransactionsFromTransactionsTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'copy:ib-transactions-from-transactions-table {year?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy IB transactions from transactions table to ib_transactions table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currentYear = $this->argument('year') ?? Carbon::now()->year;
        
        // Create the yearly table if it doesn't exist
        $tableCreated = IBTransactionService::createIBTransactionTable($currentYear);
        if (!$tableCreated) {
            $this->error("Failed to create IB transactions table for {$currentYear}");
            return Command::FAILURE;
        }

        $transactions = Transaction::where('type', 'ib_bonus')
            ->whereYear('created_at', $currentYear)
            ->get();

        $count = 0;
        $insertData = [];
        foreach ($transactions as $transaction) {
            $insertData[] = [
                'user_id' => $transaction->user_id,
                'from_user_id' => $transaction->from_user_id,
                'from_model' => $transaction->from_model,
                'target_id' => $transaction->target_id,
                'target_type' => $transaction->target_type,
                'is_level' => $transaction->is_level,
                'tnx' => $transaction->tnx,
                'description' => $transaction->description,
                'amount' => $transaction->amount,
                'type' => $transaction->type,
                'charge' => $transaction->charge,
                'final_amount' => $transaction->final_amount,
                'method' => $transaction->method,
                'pay_currency' => $transaction->pay_currency,
                'pay_amount' => $transaction->pay_amount,
                'manual_field_data' => $transaction->manual_field_data,
                'approval_cause' => $transaction->approval_cause,
                'action_by' => $transaction->action_by,
                'status' => $transaction->status,
                'created_at' => Carbon::parse($transaction->created_at)->toDateTimeString(),
                'updated_at' => Carbon::parse($transaction->updated_at)->toDateTimeString(),
            ];
        }

        if (!empty($insertData)) {
            foreach (array_chunk($insertData, 1000) as $chunk) {    
                DB::table('ib_transactions_' . $currentYear)->insert($chunk);
            }
            $count = count($insertData);
        }

        $this->info("Successfully copied {$count} IB transactions to the {$currentYear} table.");
        return Command::SUCCESS;
    }
}
