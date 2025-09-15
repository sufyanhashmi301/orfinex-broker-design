<?php

namespace Database\Seeders;

use App\Models\WithdrawAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateWithdrawAccountCredentialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->command->info('Starting to update withdraw_accounts credentials format...');
        
        // Get all withdraw accounts with credentials
        $withdrawAccounts = WithdrawAccount::whereNotNull('credentials')
            ->where('credentials', '!=', '')
            ->get();

        $updatedCount = 0;
        $errorCount = 0;

        foreach ($withdrawAccounts as $account) {
            try {
                $credentials = $account->credentials;
                
                // Check if credentials is in the old format (escaped JSON string)
                if ($this->isOldFormat($credentials)) {
                    $this->command->info("Processing account ID: {$account->id}");
                    $this->command->info("Old format: {$credentials}");
                    
                    // Convert from old format to new format
                    $newCredentials = $this->convertCredentialsFormat($credentials);
                    
                    if ($newCredentials !== null) {
                        // Update the record
                        $account->update(['credentials' => $newCredentials]);
                        $updatedCount++;
                        
                        $this->command->info("New format: {$newCredentials}");
                        $this->command->info("Account ID {$account->id} updated successfully.");
                        $this->command->line('---');
                    } else {
                        $this->command->error("Failed to convert credentials for account ID: {$account->id}");
                        $errorCount++;
                    }
                } else {
                    $this->command->info("Account ID {$account->id} already in correct format, skipping.");
                }
                
            } catch (\Exception $e) {
                $this->command->error("Error processing account ID {$account->id}: " . $e->getMessage());
                Log::error("Error updating withdraw account credentials", [
                    'account_id' => $account->id,
                    'error' => $e->getMessage(),
                    'credentials' => $account->credentials
                ]);
                $errorCount++;
            }
        }

        $this->command->info("Update completed!");
        $this->command->info("Total accounts processed: " . $withdrawAccounts->count());
        $this->command->info("Successfully updated: {$updatedCount}");
        $this->command->info("Errors: {$errorCount}");
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
