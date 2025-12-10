<?php

namespace App\Services;

use App\Models\ForexAccount;
use App\Models\Transaction;
use App\Enums\TxnType;
use App\Enums\ForexAccountStatus;
use App\Traits\NotifyTrait;
use App\Mail\MailSend;
use App\Models\EmailTemplate;
use App\Exports\ForexStatementMultiSheetExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class ForexAccountStatementService
{
    use NotifyTrait;

    protected MT5DatabaseService $mt5Service;
    protected ForexApiService $forexApiService;

    public function __construct(MT5DatabaseService $mt5Service, ForexApiService $forexApiService)
    {
        $this->mt5Service = $mt5Service;
        $this->forexApiService = $forexApiService;
    }

    /**
     * Generate comprehensive statement data for a Forex account
     *
     * @param ForexAccount $account
     * @param Carbon $statementDate
     * @return array
     */
    public function generateStatement(ForexAccount $account, Carbon $statementDate): array
    {
        try {
            // Sync account balance from MT5 before generating statement
            $syncedAccount = $this->syncAccountBalance($account);
            
            // Calculate statement period (previous day: 00:00 to 23:59)
            $startDate = $statementDate->copy()->startOfDay();
            $endDate = $statementDate->copy()->endOfDay();

            // Get opening balance (balance at start of period)
            $openingBalance = $this->getOpeningBalance($account, $startDate);
            
            // Get closing balance (synced from MT5)
            $closingBalance = (float) $syncedAccount->balance;
            $closingEquity = (float) $syncedAccount->equity;
            $freeMargin = (float) $syncedAccount->free_margin;
            $usedMargin = $closingBalance - $freeMargin;
            $marginLevel = $usedMargin > 0 ? ($closingEquity / $usedMargin * 100) : 0;

            // Get transactions (deposits/withdrawals)
            $transactions = $this->getStatementTransactions($account, $startDate, $endDate);
            
            // Get open positions
            $openPositions = $this->getOpenPositions($account);
            
            // Get closed trades
            $closedTrades = $this->getClosedTrades($account, $startDate, $endDate);
            
            // Calculate Daily P/L
            $plSummary = $this->calculateDailyPL($account, $startDate, $endDate, $openPositions, $closedTrades);

            // Calculate net balance change
            $netBalanceChange = $closingBalance - $openingBalance;

            // Calculate transaction totals
            $totalDeposits = $transactions->whereIn('type', [TxnType::Deposit, TxnType::ManualDeposit, TxnType::VoucherDeposit])
                ->sum('amount');
            $totalWithdrawals = $transactions->whereIn('type', [TxnType::Withdraw, TxnType::WithdrawAuto])
                ->sum('amount');
            $netCashFlow = $totalDeposits - $totalWithdrawals;

            // Validate balance equation
            $expectedBalance = $openingBalance + $totalDeposits - $totalWithdrawals + $plSummary['net_daily_pl'];
            $balanceDifference = abs($closingBalance - $expectedBalance);

            return [
                // Account Information
                'account' => [
                    'id' => $account->id,
                    'login' => $account->login,
                    'account_name' => $account->account_name,
                    'account_type' => $account->account_type,
                    'currency' => $account->currency,
                    'server' => $account->server,
                    'group' => $account->group,
                    'leverage' => $account->leverage,
                ],
                
                // Statement Period
                'statement_date' => $statementDate->format('Y-m-d'),
                'start_date' => $startDate->format('Y-m-d H:i:s'),
                'end_date' => $endDate->format('Y-m-d H:i:s'),
                
                // Account Balance
                'balance' => [
                    'opening' => $openingBalance,
                    'closing' => $closingBalance,
                    'net_change' => $netBalanceChange,
                ],
                
                // Equity, Margin, and Free Margin
                'equity_margin' => [
                    'opening_equity' => $this->getOpeningEquity($account, $startDate),
                    'closing_equity' => $closingEquity,
                    'equity' => $closingEquity,
                    'used_margin' => $usedMargin,
                    'free_margin' => $freeMargin,
                    'margin_level' => round($marginLevel, 2),
                    'credit' => (float) $syncedAccount->credit,
                ],
                
                // Daily Profit and Loss
                'profit_loss' => $plSummary,
                
                // Open Positions
                'open_positions' => $openPositions,
                'open_positions_count' => count($openPositions),
                
                // Closed Trades
                'closed_trades' => $closedTrades,
                'closed_trades_count' => count($closedTrades),
                
                // Transactions
                'transactions' => $transactions->toArray(),
                'transaction_summary' => [
                    'total_deposits' => $totalDeposits,
                    'total_withdrawals' => $totalWithdrawals,
                    'net_cash_flow' => $netCashFlow,
                ],
                
                // Validation
                'validation' => [
                    'expected_balance' => $expectedBalance,
                    'actual_balance' => $closingBalance,
                    'difference' => $balanceDifference,
                    'is_valid' => $balanceDifference < 0.01, // Allow small rounding differences
                ],
            ];
        } catch (\Exception $e) {
            Log::error('Failed to generate statement', [
                'account_id' => $account->id,
                'login' => $account->login,
                'statement_date' => $statementDate->format('Y-m-d'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            throw $e;
        }
    }

    /**
     * Sync account balance from MT5
     *
     * @param ForexAccount $account
     * @return ForexAccount
     */
    public function syncAccountBalance(ForexAccount $account): ForexAccount
    {
        if (!$account->login) {
            Log::warning('Cannot sync balance: account has no login', [
                'account_id' => $account->id,
            ]);
            return $account;
        }

        try {
            // Try API method first (more reliable)
            $response = $this->forexApiService->getBalance(['login' => $account->login]);
            
            if ($response['success'] && isset($response['result'])) {
                $result = $response['result'];
                
                // API returns data with capitalized keys (Balance, Equity, Credit, MarginFree)
                $account->balance = (float) ($result['Balance'] ?? $result['balance'] ?? $account->balance);
                $account->equity = (float) ($result['Equity'] ?? $result['equity'] ?? $account->equity);
                $account->credit = (float) ($result['Credit'] ?? $result['credit'] ?? $account->credit);
                $account->free_margin = (float) ($result['MarginFree'] ?? $result['marginFree'] ?? $account->free_margin);
                
                $account->save();
                
                Log::debug('Account balance synced via API', [
                    'account_id' => $account->id,
                    'login' => $account->login,
                    'balance' => $account->balance,
                    'equity' => $account->equity,
                ]);
            } else {
                // Fallback to MT5 database
                $this->syncBalanceFromMT5Database($account);
            }
        } catch (\Exception $e) {
            Log::warning('API balance sync failed, trying MT5 database', [
                'account_id' => $account->id,
                'login' => $account->login,
                'error' => $e->getMessage(),
            ]);
            
            // Fallback to MT5 database
            $this->syncBalanceFromMT5Database($account);
        }

        return $account->fresh();
    }

    /**
     * Sync balance from MT5 database (fallback method)
     *
     * @param ForexAccount $account
     * @return void
     */
    protected function syncBalanceFromMT5Database(ForexAccount $account): void
    {
        try {
            $login = (int) $account->login; // Cast to integer for MT5 query
            
            $mt5Account = $this->mt5Service->executeWithTimeout(function () use ($login) {
                return DB::connection('mt5_db')
                    ->table('mt5_accounts')
                    ->where('Login', $login)
                    ->first();
            }, null);

            if ($mt5Account) {
                $account->balance = (float) ($mt5Account->Balance ?? $account->balance);
                $account->equity = (float) ($mt5Account->Equity ?? $account->equity);
                $account->credit = (float) ($mt5Account->Credit ?? $account->credit);
                $account->free_margin = (float) ($mt5Account->MarginFree ?? $account->free_margin);
                
                $account->save();
                
                Log::debug('Account balance synced via MT5 database', [
                    'account_id' => $account->id,
                    'login' => $account->login,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('MT5 database balance sync failed', [
                'account_id' => $account->id,
                'login' => $account->login,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get opening balance at start of statement period
     *
     * @param ForexAccount $account
     * @param Carbon $startDate
     * @return float
     */
    protected function getOpeningBalance(ForexAccount $account, Carbon $startDate): float
    {
        // Get the last transaction before the statement period
        $lastTransaction = Transaction::where('target_id', (string) $account->login)
            ->whereIn('target_type', ['forex_deposit', 'forex_withdraw', 'forex'])
            ->where('created_at', '<', $startDate)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastTransaction) {
            // Calculate balance based on last transaction
            // This is approximate - actual opening balance should come from MT5 history if available
            return (float) $account->balance;
        }

        // If no previous transactions, use current balance as opening
        return (float) $account->balance;
    }

    /**
     * Get opening equity at start of statement period
     *
     * @param ForexAccount $account
     * @param Carbon $startDate
     * @return float
     */
    protected function getOpeningEquity(ForexAccount $account, Carbon $startDate): float
    {
        // For now, use current equity as opening equity
        // In future, could query MT5 history for accurate opening equity
        return (float) $account->equity;
    }

    /**
     * Get statement transactions (deposits/withdrawals) for the period
     *
     * @param ForexAccount $account
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return Collection
     */
    public function getStatementTransactions(ForexAccount $account, Carbon $startDate, Carbon $endDate): Collection
    {
        return Transaction::where('target_id', (string) $account->login)
            ->whereIn('target_type', ['forex_deposit', 'forex_withdraw', 'forex'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'tnx' => $transaction->tnx,
                    'type' => $transaction->type->value,
                    'amount' => (float) $transaction->amount,
                    'currency' => $transaction->currency ?? 'USD',
                    'description' => $transaction->description,
                    'status' => $transaction->status->value,
                    'created_at' => $transaction->created_at,
                ];
            });
    }

    /**
     * Get open positions from MT5
     *
     * @param ForexAccount $account
     * @return array
     */
    public function getOpenPositions(ForexAccount $account): array
    {
        if (!$account->login) {
            Log::warning('Cannot get open positions: account has no login', [
                'account_id' => $account->id,
            ]);
            return [];
        }

        try {
            $login = (int) $account->login; // Cast to integer for MT5 query
            
            $positions = $this->mt5Service->executeWithTimeout(function () use ($login) {
                // mt5_positions table contains only open positions, no need for VolumeClosed check
                return DB::connection('mt5_db')
                    ->table('mt5_positions')
                    ->where('Login', $login)
                    ->get();
            }, collect());
            
            Log::debug('Open positions query executed', [
                'account_id' => $account->id,
                'login' => $login,
                'positions_count' => $positions->count(),
            ]);

            return $positions->map(function ($position) {
                $profit = (float) ($position->Profit ?? 0);
                $swap = (float) ($position->Swap ?? 0);
                $commission = (float) ($position->Commission ?? 0);
                $unrealizedPL = $profit + $swap - $commission;

                return [
                    'deal' => $position->Deal ?? null,
                    'symbol' => $position->Symbol ?? '',
                    'volume' => (float) ($position->Volume ?? 0),
                    'volume_closed' => (float) ($position->VolumeClosed ?? 0),
                    'open_price' => (float) ($position->PriceOpen ?? 0),
                    'current_price' => (float) ($position->PriceCurrent ?? 0),
                    'profit' => $profit,
                    'swap' => $swap,
                    'commission' => $commission,
                    'unrealized_pl' => $unrealizedPL,
                    'time' => $position->Time ?? null,
                    'action' => $position->Action ?? null, // 0 = Buy, 1 = Sell
                    'direction' => isset($position->Action) && $position->Action == 1 ? 'Sell' : 'Buy',
                ];
            })->toArray();
        } catch (\Exception $e) {
            Log::error('Failed to get open positions', [
                'account_id' => $account->id,
                'login' => $account->login,
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * Get closed trades from MT5 for the statement period
     *
     * @param ForexAccount $account
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    public function getClosedTrades(ForexAccount $account, Carbon $startDate, Carbon $endDate): array
    {
        if (!$account->login) {
            Log::warning('Cannot get closed trades: account has no login', [
                'account_id' => $account->id,
            ]);
            return [];
        }

        try {
            $login = (int) $account->login; // Cast to integer for MT5 query
            $year = $endDate->year;
            $table = "mt5_deals_{$year}";

            Log::debug('Querying closed trades', [
                'account_id' => $account->id,
                'login' => $login,
                'table' => $table,
                'start_date' => $startDate->format('Y-m-d H:i:s'),
                'end_date' => $endDate->format('Y-m-d H:i:s'),
            ]);

            $trades = $this->mt5Service->executeWithTimeout(function () use ($login, $startDate, $endDate, $table) {
                // Use lowercase 'login' to match PositionController and MT5 database schema
                // For closed trades, we need to find the closing deal and match it with opening deal
                return DB::connection('mt5_db')
                    ->table($table)
                    ->where('login', $login) // Column is lowercase 'login' in mt5_deals tables
                    ->whereIn('Action', [0, 1]) // Buy/Sell actions only
                    ->whereColumn('Volume', '=', 'VolumeClosed') // Fully closed trades
                    ->whereBetween('Time', [$startDate, $endDate])
                    ->orderBy('Time', 'asc')
                    ->get();
            }, collect());
            
            // Log sample trade data for debugging
            if ($trades->count() > 0) {
                $sampleTrade = $trades->first();
                Log::debug('Sample closed trade data', [
                    'deal' => $sampleTrade->Deal ?? $sampleTrade->deal ?? null,
                    'order' => $sampleTrade->Order ?? $sampleTrade->order ?? null,
                    'fields' => array_keys((array)$sampleTrade),
                    'PriceOpen' => $sampleTrade->PriceOpen ?? null,
                    'PriceEntry' => $sampleTrade->PriceEntry ?? null,
                    'Price' => $sampleTrade->Price ?? null,
                    'PriceClose' => $sampleTrade->PriceClose ?? null,
                    'Profit' => $sampleTrade->Profit ?? null,
                ]);
            }
            
            Log::debug('Closed trades query executed', [
                'account_id' => $account->id,
                'login' => $login,
                'trades_count' => $trades->count(),
            ]);

            return $trades->map(function ($trade) use ($table, $login) {
                $profit = (float) ($trade->Profit ?? 0);
                $swap = (float) ($trade->Swap ?? 0);
                $commission = (float) ($trade->Commission ?? 0);
                $realizedPL = $profit + $swap - $commission;

                // Try multiple field names for open price (MT5 field names can vary)
                // PricePosition is the position's open price in MT5 deals table
                $openPrice = (float) ($trade->PricePosition ?? $trade->PriceOpen ?? $trade->PriceEntry ?? $trade->price_position ?? $trade->price_open ?? $trade->price_entry ?? 0);
                $closePrice = (float) ($trade->Price ?? $trade->PriceClose ?? $trade->price ?? $trade->price_close ?? 0);
                
                // If open price is 0, try to find the opening deal for this order
                // Note: For closed trades, the closing deal might not have PriceOpen
                // We need to find the opening deal (where Volume != VolumeClosed) for the same Order
                if ($openPrice == 0 && isset($trade->Order)) {
                    try {
                        $orderId = $trade->Order ?? $trade->order;
                        // Search in the same year table for the opening deal
                        $openingDeal = $this->mt5Service->executeWithTimeout(function () use ($table, $login, $orderId) {
                            return DB::connection('mt5_db')
                                ->table($table)
                                ->where('login', $login)
                                ->where('Order', $orderId)
                                ->whereIn('Action', [0, 1])
                                ->whereColumn('Volume', '<>', 'VolumeClosed') // Opening deal (not fully closed)
                                ->orderBy('Time', 'asc')
                                ->first();
                        }, null);
                        
                        if ($openingDeal) {
                            $openPrice = (float) ($openingDeal->PricePosition ?? $openingDeal->Price ?? $openingDeal->PriceOpen ?? $openingDeal->PriceEntry ?? 0);
                            Log::debug('Found opening deal for closed trade', [
                                'order' => $orderId,
                                'opening_price' => $openPrice,
                                'closing_price' => $closePrice,
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::debug('Could not find opening deal', [
                            'order' => $trade->Order ?? null,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
                
                // If open price is still 0, try to calculate from profit
                // Note: MT5 profit is in account currency, need to account for contract size
                if ($openPrice == 0 && $closePrice > 0) {
                    $volume = (float) ($trade->Volume ?? 1);
                    $action = $trade->Action ?? $trade->action ?? 0;
                    
                    if ($volume > 0) {
                        // Use raw profit for calculation (before swap/commission adjustments)
                        // For XAUUSD: 1 lot = 100 oz, 1 point = $0.01 per oz
                        // Profit = (PriceDiff) * Volume * ContractSize
                        // For Sell: Profit = (OpenPrice - ClosePrice) * Volume * 100
                        // For Buy: Profit = (ClosePrice - OpenPrice) * Volume * 100
                        
                        // Approximate calculation (contract size = 100 for XAUUSD)
                        $contractSize = 100; // Standard for XAUUSD
                        $priceDiff = $profit / ($volume * $contractSize);
                        
                        if ($action == 1) { // Sell
                            $openPrice = $closePrice + $priceDiff;
                        } else { // Buy
                            $openPrice = $closePrice - $priceDiff;
                        }
                    }
                }
                
                // Log if we had to calculate open price
                if ($openPrice > 0 && ($trade->PriceOpen ?? $trade->PriceEntry ?? null) == null) {
                    Log::debug('Calculated open price for closed trade', [
                        'deal' => $trade->Deal ?? $trade->deal,
                        'calculated_open_price' => $openPrice,
                        'close_price' => $closePrice,
                        'profit' => $profit,
                        'volume' => $trade->Volume ?? $trade->volume,
                    ]);
                }

                // Format time
                $time = $trade->Time ?? null;
                $openTime = $time ? Carbon::parse($time)->format('Y-m-d H:i:s') : null;
                $closeTime = $time ? Carbon::parse($time)->format('Y-m-d H:i:s') : null;

                return [
                    'deal' => $trade->Deal ?? $trade->deal ?? null,
                    'order' => $trade->Order ?? $trade->order ?? null,
                    'symbol' => $trade->Symbol ?? $trade->symbol ?? '',
                    'volume' => (float) ($trade->Volume ?? $trade->volume ?? 0),
                    'open_time' => $openTime,
                    'close_time' => $closeTime,
                    'open_price' => $openPrice,
                    'close_price' => $closePrice,
                    'profit' => $profit,
                    'swap' => $swap,
                    'commission' => $commission,
                    'realized_pl' => $realizedPL,
                    'action' => $trade->Action ?? $trade->action ?? null,
                    'direction' => isset($trade->Action) && ($trade->Action == 1 || ($trade->action ?? 0) == 1) ? 'Sell' : 'Buy',
                ];
            })->toArray();
        } catch (\Exception $e) {
            Log::error('Failed to get closed trades', [
                'account_id' => $account->id,
                'login' => $account->login,
                'start_date' => $startDate->format('Y-m-d H:i:s'),
                'end_date' => $endDate->format('Y-m-d H:i:s'),
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * Calculate Daily Profit and Loss
     *
     * @param ForexAccount $account
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param array $openPositions
     * @param array $closedTrades
     * @return array
     */
    public function calculateDailyPL(
        ForexAccount $account,
        Carbon $startDate,
        Carbon $endDate,
        array $openPositions = [],
        array $closedTrades = []
    ): array {
        // Calculate Realized P/L from closed trades
        $realizedPL = collect($closedTrades)->sum('realized_pl');
        $realizedSwap = collect($closedTrades)->sum('swap');
        $realizedCommission = collect($closedTrades)->sum('commission');

        // Calculate Unrealized P/L from open positions
        $unrealizedPL = collect($openPositions)->sum('unrealized_pl');
        $unrealizedSwap = collect($openPositions)->sum('swap');
        $unrealizedCommission = collect($openPositions)->sum('commission');

        // Total Swap (from both closed trades and open positions)
        $totalSwap = $realizedSwap + $unrealizedSwap;

        // Total Commission (from both closed trades and open positions)
        $totalCommission = $realizedCommission + $unrealizedCommission;

        // Net Daily P/L
        $netDailyPL = $realizedPL + $unrealizedPL;

        return [
            'realized_pl' => round($realizedPL, 2),
            'unrealized_pl' => round($unrealizedPL, 2),
            'total_swap' => round($totalSwap, 2),
            'total_commission' => round($totalCommission, 2),
            'net_daily_pl' => round($netDailyPL, 2),
        ];
    }

    /**
     * Send statement email to user
     *
     * @param ForexAccount $account
     * @param array $statementData
     * @return bool
     */
    public function sendStatementEmail(ForexAccount $account, array $statementData): bool
    {
        try {
            $user = $account->user;
            
            if (!$user || !$user->email) {
                Log::warning('Cannot send statement email: user or email not found', [
                    'account_id' => $account->id,
                    'user_id' => $account->user_id,
                ]);
                return false;
            }

            // Generate Excel file
            $excelPath = $this->generateStatementExcel($account, $statementData);
            
            if (!$excelPath) {
                Log::error('Failed to generate Excel for statement email', [
                    'account_id' => $account->id,
                    'login' => $account->login,
                ]);
                return false;
            }

            // Prepare shortcodes for email template (including open positions and closed trades tables)
            $shortcodes = $this->prepareStatementShortcodes($account, $statementData);

            Log::info('Attempting to send statement email with Excel', [
                'account_id' => $account->id,
                'login' => $account->login,
                'user_email' => $user->email,
                'statement_date' => $statementData['statement_date'],
                'excel_path' => $excelPath,
            ]);

            // Get email template
            $template = EmailTemplate::where('status', true)
                ->where('code', 'forex_daily_account_statement')
                ->first();

            if (!$template) {
                Log::error('Email template not found', [
                    'template_code' => 'forex_daily_account_statement',
                ]);
                return false;
            }

            // Prepare email details
            $find = array_keys($shortcodes);
            $replace = array_values($shortcodes);
            $details = [
                'subject' => str_replace($find, $replace, $template->subject),
                'banner' => asset($template->banner),
                'title' => str_replace($find, $replace, $template->title),
                'salutation' => str_replace($find, $replace, $template->salutation),
                'message_body' => str_replace($find, $replace, $template->message_body),
                'button_level' => $template->button_level,
                'button_link' => str_replace($find, $replace, $template->button_link),
                'footer_status' => $template->footer_status,
                'footer_body' => str_replace($find, $replace, $template->footer_body),
                'bottom_status' => $template->bottom_status,
                'bottom_title' => str_replace($find, $replace, $template->bottom_title),
                'bottom_body' => str_replace($find, $replace, $template->bottom_body),
                'note' => str_replace($find, $replace, $template->note),
                'support_link' => str_replace($find, $replace, $template->support_link),
                'warning_content' => str_replace($find, $replace, $template->warning_content),
                'company_info' => str_replace($find, $replace, $template->company_info),
                'site_logo' => asset(setting('site_logo', 'global')),
                'site_title' => setting('site_title', 'global'),
                'site_link' => route('home'),
                'is_risk_warning' => $template->is_risk_warning,
                'is_disclaimer' => $template->is_disclaimer,
                'use_custom_html' => $template->use_custom_html,
                'custom_html_content' => str_replace($find, $replace, $template->getDecodedCustomHtml()),
            ];

            // Generate Excel filename
            $excelFileName = 'statement_' . $account->login . '_' . $statementData['statement_date'] . '.xlsx';

            // Send email with Excel attachment
            Mail::to($user->email)->send(new MailSend($details, $excelPath, $excelFileName));

            // Clean up Excel file after sending
            if (file_exists($excelPath)) {
                @unlink($excelPath);
            }

            Log::info('Statement email sent successfully with Excel', [
                'account_id' => $account->id,
                'login' => $account->login,
                'user_email' => $user->email,
                'statement_date' => $statementData['statement_date'],
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send statement email', [
                'account_id' => $account->id,
                'login' => $account->login,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Generate Excel file for statement
     *
     * @param ForexAccount $account
     * @param array $statementData
     * @return string|null Path to generated Excel file
     */
    protected function generateStatementExcel(ForexAccount $account, array $statementData): ?string
    {
        try {
            // Create temporary file path
            $fileName = 'statement_' . $account->login . '_' . time() . '.xlsx';
            $tempPath = storage_path('app/temp/' . $fileName);
            
            // Ensure directory exists
            $dir = dirname($tempPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            
            // Generate Excel with multiple sheets
            $export = new ForexStatementMultiSheetExport($account, $statementData);
            
            // Use Excel::store with proper path
            Excel::store(
                $export,
                'temp/' . $fileName,
                'local',
                \Maatwebsite\Excel\Excel::XLSX
            );
            
            $excelPath = storage_path('app/temp/' . $fileName);
            
            return file_exists($excelPath) ? $excelPath : null;
        } catch (\Exception $e) {
            Log::error('Failed to generate Excel', [
                'account_id' => $account->id,
                'login' => $account->login,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Prepare shortcodes for statement email template
     *
     * @param ForexAccount $account
     * @param array $statementData
     * @return array
     */
    public function prepareStatementShortcodes(ForexAccount $account, array $statementData): array
    {
        $user = $account->user;
        $balance = $statementData['balance'];
        $equityMargin = $statementData['equity_margin'];
        $profitLoss = $statementData['profit_loss'];
        $openPositions = $statementData['open_positions'];
        $closedTrades = $statementData['closed_trades'];
        $transactionSummary = $statementData['transaction_summary'];

        // Format HTML tables for positions and trades
        $openPositionsHtml = $this->formatOpenPositionsTable($openPositions);
        $closedTradesHtml = $this->formatClosedTradesTable($closedTrades);

        return [
            // User and Account Info
            '[[full_name]]' => $user->full_name ?? $user->first_name . ' ' . $user->last_name,
            '[[account_name]]' => $account->account_name,
            '[[account_login]]' => $account->login,
            '[[account_type]]' => ucfirst($account->account_type),
            '[[statement_date]]' => $statementData['statement_date'],
            
            // Account Balance
            '[[opening_balance]]' => number_format($balance['opening'], 2),
            '[[closing_balance]]' => number_format($balance['closing'], 2),
            '[[net_balance_change]]' => number_format($balance['net_change'], 2),
            
            // Equity, Margin, and Free Margin
            '[[opening_equity]]' => number_format($equityMargin['opening_equity'] ?? 0, 2),
            '[[closing_equity]]' => number_format($equityMargin['closing_equity'], 2),
            '[[equity]]' => number_format($equityMargin['equity'], 2),
            '[[used_margin]]' => number_format($equityMargin['used_margin'], 2),
            '[[free_margin]]' => number_format($equityMargin['free_margin'], 2),
            '[[margin_level]]' => number_format($equityMargin['margin_level'], 2),
            '[[credit]]' => number_format($equityMargin['credit'], 2),
            
            // Daily Profit and Loss
            '[[realized_pl]]' => number_format($profitLoss['realized_pl'], 2),
            '[[unrealized_pl]]' => number_format($profitLoss['unrealized_pl'], 2),
            '[[total_swap]]' => number_format($profitLoss['total_swap'], 2),
            '[[total_commission]]' => number_format($profitLoss['total_commission'], 2),
            '[[net_daily_pl]]' => number_format($profitLoss['net_daily_pl'], 2),
            
            // Position/Trade Summary
            '[[open_positions_count]]' => $statementData['open_positions_count'],
            '[[closed_trades_count]]' => $statementData['closed_trades_count'],
            '[[total_deposits]]' => number_format($transactionSummary['total_deposits'], 2),
            '[[total_withdrawals]]' => number_format($transactionSummary['total_withdrawals'], 2),
            
            // Account Details
            '[[leverage]]' => $account->leverage,
            '[[currency]]' => $account->currency,
            '[[server]]' => $account->server ?? 'N/A',
            '[[group]]' => $account->group ?? 'N/A',
            
            // HTML Content
            '[[open_positions_html]]' => $openPositionsHtml,
            '[[closed_trades_html]]' => $closedTradesHtml,
            
            // Site Info
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ];
    }

    /**
     * Format open positions as HTML table
     *
     * @param array $positions
     * @return string
     */
    protected function formatOpenPositionsTable(array $positions): string
    {
        if (empty($positions)) {
            return '<p>No open positions.</p>';
        }

        $html = '<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse:collapse;">';
        $html .= '<thead><tr>';
        $html .= '<th>Symbol</th><th>Direction</th><th>Volume</th><th>Open Price</th><th>Current Price</th><th>Unrealized P/L</th>';
        $html .= '</tr></thead><tbody>';

        foreach ($positions as $position) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($position['symbol']) . '</td>';
            $html .= '<td>' . htmlspecialchars($position['direction']) . '</td>';
            $html .= '<td>' . number_format($position['volume'], 2) . '</td>';
            $html .= '<td>' . number_format($position['open_price'], 5) . '</td>';
            $html .= '<td>' . number_format($position['current_price'], 5) . '</td>';
            $html .= '<td>' . number_format($position['unrealized_pl'], 2) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';
        return $html;
    }

    /**
     * Format closed trades as HTML table
     *
     * @param array $trades
     * @return string
     */
    protected function formatClosedTradesTable(array $trades): string
    {
        if (empty($trades)) {
            return '<p>No closed trades for this period.</p>';
        }

        $html = '<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse:collapse;">';
        $html .= '<thead><tr>';
        $html .= '<th>Deal</th><th>Symbol</th><th>Direction</th><th>Volume</th><th>Open Price</th><th>Close Price</th><th>Realized P/L</th>';
        $html .= '</tr></thead><tbody>';

        foreach ($trades as $trade) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($trade['deal'] ?? 'N/A') . '</td>';
            $html .= '<td>' . htmlspecialchars($trade['symbol']) . '</td>';
            $html .= '<td>' . htmlspecialchars($trade['direction']) . '</td>';
            $html .= '<td>' . number_format($trade['volume'], 2) . '</td>';
            $html .= '<td>' . number_format($trade['open_price'], 5) . '</td>';
            $html .= '<td>' . number_format($trade['close_price'], 5) . '</td>';
            $html .= '<td>' . number_format($trade['realized_pl'], 2) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';
        return $html;
    }

}

