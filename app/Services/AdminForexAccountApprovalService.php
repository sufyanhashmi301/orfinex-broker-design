<?php

namespace App\Services;

use App\Enums\ForexAccountStatus;
use App\Enums\TraderType;
use App\Models\ForexAccount;
use App\Models\ForexSchema;
use Illuminate\Support\Facades\DB;

class AdminForexAccountApprovalService
{
    protected ForexApiService $forexApiService;

    public function __construct(ForexApiService $forexApiService)
    {
        $this->forexApiService = $forexApiService;
    }

    /**
     * Approve a pending/canceled account by creating it on the trading platform and updating local record.
     * Returns ['success'=>bool, 'message'=>string]
     */
    public function approve(ForexAccount $account): array
    {
        $account->loadMissing(['schema', 'user']);
        /** @var ForexSchema $schema */
        $schema = $account->schema;
        if (!$schema) {
            return ['success' => false, 'message' => __('Schema not found for the account.')];
        }

        $accountType = $account->account_type; // 'real' or 'demo'
        $traderType = $schema->trader_type; // TraderType::MT5 / X9

        $server = $this->getServer($accountType, $traderType);
        $group = $account->group; // Was chosen at request time
        $password = null;
        $investorPassword = null;
        // meta is stored as JSON string in DB; decode safely
        if (!empty($account->meta)) {
            $meta = json_decode($account->meta, true);
            if (is_array($meta) && !empty($meta['master_password'])) {
                $password = (string) $meta['master_password'];
            }
            if (is_array($meta) && !empty($meta['investor_password'])) {
                $investorPassword = (string) $meta['investor_password'];
            }
        }
        if (!$password) {
            // Generate a compliant temporary password (8–20 chars, 4 types)
            $password = $this->generateCompliantPassword();
        }
        if (!$investorPassword) {
            $investorPassword = 'Inv@Pass1!';
        }

        // Prepare login within range if applicable (MT5 only)
        $loginPreferred = 0;
        if ($traderType === TraderType::MT5 && setting('is_forex_group_range', 'global')) {
            $loginPreferred = $this->getNextLoginWithinRange($schema);
        }

        if ($traderType === TraderType::MT5) {
            $leverageValue = $this->normalizeLeverage($account->leverage);
            $data = [
                'login' => $loginPreferred,
                'group' => $group,
                'firstName' => $account->user->first_name,
                'middleName' => '',
                'lastName' => $account->user->last_name,
                'leverage' => $leverageValue,
                'rights' => 'USER_RIGHT_ALL',
                'country' => $account->user->country,
                'city' => $account->user->city,
                'state' => '',
                'zipCode' => $account->user->zip_code,
                'address' => $account->user->address,
                'phone' => $account->user->phone ?: '+91',
                'email' => $account->user->email,
                'agent' => 0,
                'account' => '',
                'company' => setting('site_title', 'global'),
                'language' => 0,
                'phonePassword' => 'SNNH@2024@bol',
                'status' => 'RE',
                'masterPassword' => $password,
                'investorPassword' => $investorPassword
            ];

            $retryCount = 0; $maxRetries = 3; $success = false; $response = null; $loginTry = $loginPreferred;
            while ($retryCount < $maxRetries) {
                $response = $accountType === 'real'
                    ? $this->forexApiService->createUser($data)
                    : $this->forexApiService->createUserDemo($data);

                if ($response['success'] ?? false) { $success = true; break; }
                \Log::warning('Forex MT5 createUser failed', ['attempt' => $retryCount + 1, 'response' => $response]);
                $loginTry++; $data['login'] = $loginTry; $retryCount++;
            }

            if (!$success) {
                return ['success' => false, 'message' => __('Failed to create account at platform. Please retry.')];
            }

            $resResult = $response['result'];
            $mt5Login = $resResult['login'] ?? null;
            if (!$mt5Login || ($resResult['responseCode'] ?? 1) !== 0) {
                return ['success' => false, 'message' => __('Platform creation failed with invalid response.')];
            }

            // Enable rights
            $this->forexApiService->setUserRights([
                'login' => $mt5Login,
                'rights' => 'USER_RIGHT_ENABLED'
            ]);

            // Update local record
            $this->finalizeAccount($account, $mt5Login, $server);
            // Immediately purge any stored master_password or investor_password from meta
            if (!empty($account->meta)) {
                $meta = json_decode($account->meta, true) ?: [];
                $changed = false;
                if (isset($meta['master_password'])) {
                    unset($meta['master_password']);
                    $changed = true;
                }
                if (isset($meta['investor_password'])) {
                    unset($meta['investor_password']);
                    $changed = true;
                }
                if ($changed) {
                    $account->meta = !empty($meta) ? json_encode($meta) : null;
                    $account->save();
                }
            }

            // Demo auto deposit
            if ($accountType === 'demo' && ($schema->demo_deposit_amount ?? 0) > 0) {
                $this->forexApiService->balanceOperationDemo([
                    'login' => $mt5Login,
                    'Amount' => $schema->demo_deposit_amount,
                    'type' => 1,
                    'TransactionComments' => 'auto/demo/deposit/' . time(),
                ]);
            }

            return [
                'success' => true,
                'message' => __('Account approved and created successfully.'),
                'login' => $mt5Login,
                'password' => $password,
                'investor_password' => $investorPassword,
                'server' => $server,
            ];
        }

        if ($traderType === TraderType::X9) {
            $data = [
                'preferred_login' => 'default',
                'client_id' => null,
                'client_group_type_id' => $accountType === 'real' ? 2 : 1,
                'client_group_id' => (int) $group,
                'first_name' => $account->user->first_name,
                'middle_name' => null,
                'last_name' => $account->user->last_name,
                'country_id' => 5,
                'phone' => $account->user->phone,
                'email' => $account->user->email,
                'company' => setting('site_title', 'global'),
                'master_password' => $password,
                'investor_password' => $investorPassword
            ];
            $x9 = new x9ApiService();
            $response = $x9->createUser($data);
            if (!($response['success'] ?? false)) {
                \Log::warning('Forex X9 createUser failed', ['response' => $response]);
                return ['success' => false, 'message' => __('Failed to create account at platform. Please retry.')];
            }
            $resResult = $response['result']['trading_account'] ?? [];
            $login = $resResult['account_number'] ?? null;
            if (!$login) {
                return ['success' => false, 'message' => __('Platform creation failed with invalid response.')];
            }
            $this->finalizeAccount($account, $login, $server);
            // Immediately purge any stored master_password or investor_password from meta
            if (!empty($account->meta)) {
                $meta = json_decode($account->meta, true) ?: [];
                $changed = false;
                if (isset($meta['master_password'])) { unset($meta['master_password']); $changed = true; }
                if (isset($meta['investor_password'])) { unset($meta['investor_password']); $changed = true; }
                if ($changed) {
                    $account->meta = !empty($meta) ? json_encode($meta) : null;
                    $account->save();
                }
            }

            // Demo auto deposit
            if ($accountType === 'demo' && ($schema->demo_deposit_amount ?? 0) > 0) {
                (new x9ApiService())->balanceOperationDemo([
                    'login' => $login,
                    'Amount' => $schema->demo_deposit_amount,
                    'type' => 1,
                    'TransactionComments' => 'auto/demo/deposit/' . time(),
                ]);
            }
            return [
                'success' => true,
                'message' => __('Account approved and created successfully.'),
                'login' => $login,
                'password' => $password,
                'server' => $server,
            ];
        }

        return ['success' => false, 'message' => __('Unsupported trader type.')];
    }

    protected function getNextLoginWithinRange(ForexSchema $schema): int
    {
        $forexAccount = ForexAccount::where('forex_schema_id', $schema->id)
            ->orderBy(DB::raw('CAST(login AS UNSIGNED)'), 'desc')
            ->first();

        if ($forexAccount) {
            $highestLogin = (int) $forexAccount->login;
            if ($highestLogin < $schema->start_range || $highestLogin >= $schema->end_range) {
                $withinRangeForexAccount = ForexAccount::where('forex_schema_id', $schema->id)
                    ->whereBetween('login', [$schema->start_range, $schema->end_range - 1])
                    ->orderBy(DB::raw('CAST(login AS UNSIGNED)'), 'desc')
                    ->first();
                if ($withinRangeForexAccount) {
                    return ((int) $withinRangeForexAccount->login) + 1;
                }
                return (int) $schema->start_range;
            } else {
                return $highestLogin + 1;
            }
        }
        return (int) $schema->start_range;
    }

    protected function getServer(string $accountType, string $traderType): string
    {
        if ($traderType === TraderType::MT5) {
            return $accountType === 'real' ? setting('live_server', 'platform_api') : setting('demo_server', 'platform_api');
        }
        if ($traderType === TraderType::X9) {
            return setting('x9_name', 'x9_api');
        }
        return '';
    }

    protected function finalizeAccount(ForexAccount $account, string $login, string $server): void
    {
        $account->login = $login;
        $account->server = $server;
        $account->status = ForexAccountStatus::Ongoing;
        $account->save();
    }

    protected function normalizeLeverage($value): int
    {
        if (is_string($value) && str_contains($value, ':')) {
            $parts = explode(':', $value, 2);
            $right = trim($parts[1] ?? '');
            return max(1, (int) $right);
        }
        if (is_numeric($value)) {
            return max(1, (int) $value);
        }
        return 100;
    }

    protected function generateCompliantPassword(): string
    {
        $upper = chr(rand(65, 90));
        $lower = chr(rand(97, 122));
        $digit = (string) rand(0, 9);
        $specials = ['!', '@', '#', '$', '%', '&', '*', '(', ')', ':', '{', '}', '|', '<', '>'];
        $special = $specials[array_rand($specials)];
        $rest = bin2hex(random_bytes(4)); // 8 hex chars
        $candidate = $upper . $lower . $digit . $special . $rest;
        // Ensure length between 8 and 20
        return substr($candidate, 0, 16);
    }
}



