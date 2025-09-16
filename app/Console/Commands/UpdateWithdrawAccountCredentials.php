<?php

namespace App\Console\Commands;

use App\Models\WithdrawAccount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateWithdrawAccountCredentials extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'withdraw-accounts:update-credentials 
                            {--dry-run : Show what would be updated without making changes}
                            {--account-id= : Update specific account ID only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update withdraw_accounts credentials from escaped JSON format to proper JSON format';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $specificAccountId = $this->option('account-id');

        $this->info('Starting to update withdraw_accounts credentials format...');
        
        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No changes will be made to the database');
        }

        // Build query
        $query = WithdrawAccount::whereNotNull('credentials')
            ->where('credentials', '!=', '');

        if ($specificAccountId) {
            $query->where('id', $specificAccountId);
            $this->info("Processing specific account ID: {$specificAccountId}");
        }

        $withdrawAccounts = $query->get();

        if ($withdrawAccounts->isEmpty()) {
            $this->warn('No withdraw accounts found with credentials to process.');
            return 0;
        }

        $updatedCount = 0;
        $errorCount = 0;
        $skippedCount = 0;

        $this->info("Found {$withdrawAccounts->count()} accounts to process.");
        
        $progressBar = $this->output->createProgressBar($withdrawAccounts->count());
        $progressBar->start();

        foreach ($withdrawAccounts as $account) {
            try {
                $credentials = $account->credentials;
                
                // Check if credentials is in the old format (escaped JSON string)
                if ($this->isOldFormat($credentials)) {
                    $this->newLine();
                    $this->info("Processing account ID: {$account->id}");
                    $this->line("Old format: {$credentials}");
                    
                    // Convert from old format to new format
                    $newCredentials = $this->convertCredentialsFormat($credentials);
                    
                    if ($newCredentials !== null) {
                        if (!$isDryRun) {
                            // Update the record
                            $account->update(['credentials' => $newCredentials]);
                        }
                        
                        $updatedCount++;
                        $this->info("New format: {$newCredentials}");
                        $this->info($isDryRun ? "[DRY RUN] Would update account ID {$account->id}" : "Account ID {$account->id} updated successfully.");
                    } else {
                        $this->error("Failed to convert credentials for account ID: {$account->id}");
                        $errorCount++;
                    }
                } else {
                    $skippedCount++;
                    if ($this->option('verbose')) {
                        $this->newLine();
                        $this->comment("Account ID {$account->id} already in correct format, skipping.");
                    }
                }
                
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Error processing account ID {$account->id}: " . $e->getMessage());
                Log::error("Error updating withdraw account credentials", [
                    'account_id' => $account->id,
                    'error' => $e->getMessage(),
                    'credentials' => $account->credentials
                ]);
                $errorCount++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info("Update completed!");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total accounts processed', $withdrawAccounts->count()],
                ['Successfully ' . ($isDryRun ? 'would be updated' : 'updated'), $updatedCount],
                ['Skipped (already correct format)', $skippedCount],
                ['Errors', $errorCount]
            ]
        );

        if ($isDryRun && $updatedCount > 0) {
            $this->warn('This was a dry run. To actually update the database, run the command without --dry-run');
        }

        return $errorCount > 0 ? 1 : 0;
    }

    /**
     * Check if credentials is in old format (escaped JSON string)
     *
     * @param string $credentials
     * @return bool
     */
    private function isOldFormat(string $credentials): bool
    {
        // Old format has escaped quotes like: "{\"Bank Name\":{\"type\":\"text\",\"validation\":\"required\",\"value\":\"Mezan\"}}"
        // New format is proper JSON: {"Bank Name":{"type":"text","validation":"required","value":"Mezan"}}
        
        // Check if it starts and ends with quotes and contains escaped quotes
        return (str_starts_with($credentials, '"') && str_ends_with($credentials, '"') && str_contains($credentials, '\\"'));
    }

    /**
     * Convert credentials from old format to new format
     *
     * @param string $oldCredentials
     * @return string|null
     */
    private function convertCredentialsFormat(string $oldCredentials): ?string
    {
        try {
            // Remove the outer quotes and unescape the inner JSON
            if (str_starts_with($oldCredentials, '"') && str_ends_with($oldCredentials, '"')) {
                // Remove outer quotes
                $unquoted = substr($oldCredentials, 1, -1);
                
                // Unescape the JSON
                $unescaped = stripslashes($unquoted);
                
                // Validate that it's proper JSON
                $decoded = json_decode($unescaped, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    // Re-encode to ensure proper formatting
                    return json_encode($decoded, JSON_UNESCAPED_SLASHES);
                }
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error("Error converting credentials format", [
                'credentials' => $oldCredentials,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}
