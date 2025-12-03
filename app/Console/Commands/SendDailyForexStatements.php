<?php

namespace App\Console\Commands;

use App\Models\ForexAccount;
use App\Enums\ForexAccountStatus;
use App\Services\ForexAccountStatementService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendDailyForexStatements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forex:send-daily-statements 
                            {--date= : Override statement date (Y-m-d format, default: yesterday)}
                            {--account-type= : Filter by account type (real/demo/both, default: from settings)}
                            {--dry-run : Test mode without sending emails}
                            {--limit= : Limit number of accounts to process (for testing)}
                            {--account= : Process specific account ID only}
                            {--login= : Process specific account by login number}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily account statements to Forex account holders';

    protected ForexAccountStatementService $statementService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ForexAccountStatementService $statementService)
    {
        parent::__construct();
        $this->statementService = $statementService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            // Check if daily statements are enabled
            if (!setting('daily_statement_enabled', 'forex_daily_reporting', 0)) {
                $this->info('Daily account statements are disabled in settings.');
                Log::info('Daily forex statements skipped - feature disabled');
                return 0;
            }

            // Get statement date
            $statementDate = $this->getStatementDate();
            
            // Get account type filter
            $accountTypeFilter = $this->getAccountTypeFilter();
            
            // Get batch size
            $batchSize = (int) setting('daily_statement_batch_size', 'forex_daily_reporting', 50);
            
            // Get accounts to process
            $accountsQuery = $this->getAccountsQuery($accountTypeFilter);
            
            // Apply limit if specified
            if ($this->option('limit')) {
                $accountsQuery->limit((int) $this->option('limit'));
            }
            
            // Apply specific account filter if specified (by ID or login)
            if ($this->option('account')) {
                $accountsQuery->where('id', $this->option('account'));
            }
            
            if ($this->option('login')) {
                $loginValue = $this->option('login');
                // First check if account exists by login
                $accountByLogin = ForexAccount::where('login', $loginValue)->first();
                
                if (!$accountByLogin) {
                    $this->error("Account with login '{$loginValue}' not found in database.");
                    return 1;
                }
                
                // Show account details for debugging
                $this->info("Account with login '{$loginValue}' details:");
                $this->table(
                    ['Property', 'Value'],
                    [
                        ['ID', $accountByLogin->id],
                        ['Login', $accountByLogin->login ?? 'NULL'],
                        ['Status', $accountByLogin->status ?? 'NULL'],
                        ['Account Type', $accountByLogin->account_type ?? 'NULL'],
                        ['User ID', $accountByLogin->user_id ?? 'NULL'],
                        ['User Email', $accountByLogin->user->email ?? 'NULL' ?? 'NULL'],
                    ]
                );
                
                // Check if account meets criteria
                $issues = [];
                if ($accountByLogin->status !== ForexAccountStatus::Ongoing) {
                    $issues[] = "Status is '{$accountByLogin->status}' (required: 'Ongoing')";
                }
                if (!$accountByLogin->login) {
                    $issues[] = "Login is NULL (required: not null)";
                }
                if ($accountTypeFilter !== 'both' && $accountByLogin->account_type !== $accountTypeFilter) {
                    $issues[] = "Account type is '{$accountByLogin->account_type}' (filter: '{$accountTypeFilter}')";
                }
                if (!$accountByLogin->user) {
                    $issues[] = "User not found";
                } elseif (!$accountByLogin->user->email) {
                    $issues[] = "User email is NULL";
                }
                
                if (!empty($issues)) {
                    $this->warn("Account does not meet processing criteria:");
                    foreach ($issues as $issue) {
                        $this->warn("  - {$issue}");
                    }
                    $this->newLine();
                    $this->info("Note: Using --login bypasses filters. Processing anyway...");
                    // Bypass filters for specific login
                    $accountsQuery = ForexAccount::query()
                        ->where('login', $loginValue)
                        ->with('user:id,email,first_name,last_name');
                } else {
                    $accountsQuery->where('login', $loginValue);
                }
            }
            
            $accounts = $accountsQuery->get();
            
            if ($accounts->isEmpty()) {
                $this->info('No accounts found to process.');
                Log::info('Daily forex statements - no accounts found', [
                    'statement_date' => $statementDate->format('Y-m-d'),
                    'account_type_filter' => $accountTypeFilter,
                ]);
                return 0;
            }

            $this->info("Processing {$accounts->count()} account(s) for statement date: {$statementDate->format('Y-m-d')}");
            
            if ($this->option('dry-run')) {
                $this->warn('DRY RUN MODE - No emails will be sent');
            }

            // Process accounts
            $stats = [
                'total' => $accounts->count(),
                'success' => 0,
                'failed' => 0,
                'skipped' => 0,
            ];

            $bar = $this->output->createProgressBar($accounts->count());
            $bar->start();

            foreach ($accounts as $account) {
                try {
                    // Check if account has login (required for statements)
                    if (!$account->login) {
                        $this->newLine();
                        $this->warn("Skipping account {$account->id} - no login");
                        Log::warning("Forex statement skipped - account {$account->id} has no login");
                        $stats['skipped']++;
                        $bar->advance();
                        continue;
                    }

                    // Check if user exists and has email
                    if (!$account->user || !$account->user->email) {
                        $this->newLine();
                        $this->warn("Skipping account {$account->id} - user or email not found");
                        Log::warning("Forex statement skipped - account {$account->id} user or email not found", [
                            'account_id' => $account->id,
                            'user_id' => $account->user_id,
                        ]);
                        $stats['skipped']++;
                        $bar->advance();
                        continue;
                    }

                    // Generate statement
                    $statementData = $this->statementService->generateStatement($account, $statementDate);
                    
                    // Send email (unless dry-run)
                    if (!$this->option('dry-run')) {
                        $sent = $this->statementService->sendStatementEmail($account, $statementData);
                        
                        if ($sent) {
                            $stats['success']++;
                            Log::info("Forex statement sent successfully", [
                                'account_id' => $account->id,
                                'account_login' => $account->login,
                                'user_id' => $account->user_id,
                                'user_email' => $account->user->email,
                                'statement_date' => $statementDate->format('Y-m-d'),
                            ]);
                        } else {
                            $stats['failed']++;
                            Log::error("Forex statement failed to send", [
                                'account_id' => $account->id,
                                'account_login' => $account->login,
                                'user_id' => $account->user_id,
                                'statement_date' => $statementDate->format('Y-m-d'),
                            ]);
                        }
                    } else {
                        $stats['success']++;
                        $this->newLine();
                        $this->info("Would send statement for account {$account->id} ({$account->login})");
                    }
                    
                } catch (\Exception $e) {
                    $stats['failed']++;
                    $this->newLine();
                    $this->error("Error processing account {$account->id}: " . $e->getMessage());
                    Log::error("Forex statement error", [
                        'account_id' => $account->id,
                        'account_login' => $account->login,
                        'statement_date' => $statementDate->format('Y-m-d'),
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
                
                $bar->advance();
            }

            $bar->finish();
            $this->newLine(2);

            // Display summary
            $this->info('=== Processing Summary ===');
            $this->table(
                ['Metric', 'Count'],
                [
                    ['Total Accounts', $stats['total']],
                    ['Successful', $stats['success']],
                    ['Failed', $stats['failed']],
                    ['Skipped', $stats['skipped']],
                ]
            );

            Log::info('Daily forex statements processing completed', [
                'statement_date' => $statementDate->format('Y-m-d'),
                'stats' => $stats,
            ]);

            return $stats['failed'] > 0 ? 1 : 0;

        } catch (\Exception $e) {
            $errorMsg = 'Fatal error in daily forex statements command: ' . $e->getMessage();
            $this->error($errorMsg);
            Log::error($errorMsg, [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return 1;
        }
    }

    /**
     * Get statement date from option or default to yesterday
     *
     * @return Carbon
     */
    protected function getStatementDate(): Carbon
    {
        if ($this->option('date')) {
            try {
                return Carbon::parse($this->option('date'));
            } catch (\Exception $e) {
                $this->warn("Invalid date format, using yesterday: " . $e->getMessage());
                return Carbon::yesterday();
            }
        }

        // Get statement period from settings
        $period = setting('daily_statement_period', 'forex_daily_reporting', 'previous_day');
        
        if ($period === 'current_day') {
            return Carbon::today();
        }
        
        return Carbon::yesterday();
    }

    /**
     * Get account type filter from option or settings
     *
     * @return string
     */
    protected function getAccountTypeFilter(): string
    {
        if ($this->option('account-type')) {
            $type = $this->option('account-type');
            if (in_array($type, ['real', 'demo', 'both'])) {
                return $type;
            }
            $this->warn("Invalid account type '{$type}', using 'both'");
        }

        return setting('daily_statement_account_types', 'forex_daily_reporting', 'both');
    }

    /**
     * Get accounts query with filters applied
     *
     * @param string $accountTypeFilter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getAccountsQuery(string $accountTypeFilter)
    {
        $query = ForexAccount::query()
            ->where('status', ForexAccountStatus::Ongoing)
            ->whereNotNull('login')
            ->with('user:id,email,first_name,last_name');

        // Apply account type filter
        if ($accountTypeFilter !== 'both') {
            $query->where('account_type', $accountTypeFilter);
        }

        return $query;
    }
}

