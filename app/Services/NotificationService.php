<?php

namespace App\Services;

use App\Models\Transaction;
use App\Traits\NotifyTrait;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    use NotifyTrait;

    /**
     * Send user notifications (email + push) based on transaction status.
     */
    public function transactionStatus(
        Transaction $txn,
        string|\BackedEnum $status,
        ?string $emailTemplateCode = null,
        ?string $pushTemplateCode = null
    ): void {
        try {
            if (!$txn->user) {
                $this->logSkip($txn->id, 'User not found');
                return;
            }

            $status  = $this->normalize($status);
            $type    = $this->normalize($txn->type);
            $short   = $this->buildShortcodes($txn, $status);
            $tpl     = $this->resolveTemplates(
                'user_email', 'user_push', $type, $status,
                $emailTemplateCode, $pushTemplateCode
            );

            // Email
            if ($this->isValidEmail($txn->user->email ?? null)) {
                $this->mailNotify($txn->user->email, $tpl['email'], $short);
            }

            // Push
            if ($tpl['push']) {
                $this->pushNotify(
                    $tpl['push'],
                    $short,
                    route('user.history.transactions'),
                    $txn->user_id,
                    'transaction'
                );
            }

        } catch (\Throwable $e) {
            $this->logError($e, $txn->id, $status);
        }
    }

    /**
     * Admin/staff notifications for transactions.
     */
    public function adminTransactionAlert(
        Transaction $txn,
        bool $sendPushNotification = true,
        ?string $emailTemplateCode = null,
        ?string $pushTemplateCode = null
    ): void {
        try {
            $status  = $this->normalize($txn->status);
            $type    = $this->normalize($txn->type);
            $short   = $this->buildShortcodes($txn, $status);
            $tpl     = $this->resolveTemplates(
                'admin_email', 'admin_push', $type, $status,
                $emailTemplateCode, $pushTemplateCode
            );

            // Get admin/staff emails
            $adminEmails = $this->getAdminEmails($txn->user_id);
            
            // Check if email template exists
            if (empty($tpl['email'])) {
                Log::warning('Admin email template not found', [
                    'transaction_id' => $txn->id,
                    'transaction_type' => $type,
                    'transaction_status' => $status,
                    'email_template_override' => $emailTemplateCode,
                ]);
                return;
            }
            
            Log::info('Sending admin/staff notifications', [
                'transaction_id' => $txn->id,
                'transaction_type' => $type,
                'transaction_status' => $status,
                'email_template' => $tpl['email'],
                'push_template' => $tpl['push'],
                'admin_emails_count' => count($adminEmails),
                'admin_emails' => $adminEmails,
            ]);

            // Send emails to all admin/staff
            $sentCount = 0;
            foreach ($adminEmails as $email) {
                if ($this->isValidEmail($email)) {
                    $result = $this->mailNotify($email, $tpl['email'], $short);
                    if ($result !== false) {
                        $sentCount++;
                    } else {
                        Log::warning('Failed to send admin email', [
                            'email' => $email,
                            'template' => $tpl['email'],
                            'transaction_id' => $txn->id,
                        ]);
                    }
                } else {
                    Log::warning('Invalid admin email skipped', [
                        'email' => $email,
                        'transaction_id' => $txn->id,
                    ]);
                }
            }

            Log::info('Admin/staff emails sent', [
                'transaction_id' => $txn->id,
                'sent_count' => $sentCount,
                'total_emails' => count($adminEmails),
            ]);

            // Send push notification broadcast (if enabled)
            if ($sendPushNotification && $tpl['push']) {
                $this->pushNotify(
                    $tpl['push'],
                    $short,
                    route('admin.transactions.view', $txn->id),
                    null,
                    'transaction'
                );
            }

        } catch (\Throwable $e) {
            $this->logError($e, $txn->id);
        }
    }

    /**
     * Normalize enums or strings to lowercase.
     */
    private function normalize(string|\BackedEnum|null $v): string
    {
        return strtolower($v instanceof \BackedEnum ? $v->value : ($v ?? ''));
    }

    /**
     * Build shortcodes for templates.
     */
    private function buildShortcodes(Transaction $txn, string $status): array
    {
        $user     = $txn->user;
        $amount   = number_format($txn->amount, 2);
        $method   = ucfirst($txn->method ?? '');
        $currency = setting('currency', 'global') ?? 'USD';

        return [
            '[[full_name]]'       => $user?->full_name ?? '',
            '[[username]]'        => $user?->username ?? '',
            '[[txn]]'             => $txn->tnx,
            '[[tnx]]'             => $txn->tnx,
            '[[transaction_id]]'  => $txn->id,
            '[[amount]]'          => $amount,
            '[[currency]]'        => $currency,
            '[[method]]'          => $method,
            '[[method_name]]'     => $method,
            '[[gateway_name]]'    => $method,
            '[[status]]'          => ucfirst($status),
            '[[description]]'     => $txn->description ?? '',
            '[[date]]'            => now()->format('Y-m-d H:i'),
            '[[site_title]]'      => setting('site_title', 'global'),
            '[[site_url]]'        => url('/'),
            '[[action_by]]'       => $this->getActionByName($txn),

            // Extra shortcuts
            '[[message]]'         => $txn->description ?? '',
            '[[deposit_amount]]'  => $amount,
            '[[withdraw_amount]]' => $amount,
        ];
    }

    /**
     * Get readable name of staff/admin who performed the action.
     */
    private function getActionByName(Transaction $txn): string
    {
        if (!$txn->action_by) {
            return '';
        }

        // already loaded staff object
        if (is_object($txn->action_by) && property_exists($txn->action_by, 'full_name')) {
            return $txn->action_by->full_name;
        }

        // relation loaded
        if ($txn->relationLoaded('staff')) {
            return $txn->staff->full_name ?? '';
        }

        // load if numeric ID
        if (is_numeric($txn->action_by)) {
            $txn->loadMissing('staff');
            return $txn->staff->full_name ?? '';
        }

        return '';
    }

    /**
     * Resolve email/push templates with fallbacks.
     */
    private function resolveTemplates(
        string $emailKey,
        string $pushKey,
        string $type,
        string $status,
        ?string $emailOverride = null,
        ?string $pushOverride = null
    ): array {
        $config = config('transaction_notifications', []);

        return [
            'email' => $emailOverride
                ?? $this->resolveEmailTemplate($config[$emailKey] ?? [], $type, $status),

            'push'  => $pushOverride
                ?? $this->resolvePushTemplate($config[$pushKey] ?? [], $type),
        ];
    }

    /**
     * Email template matching with fallback chain.
     */
    private function resolveEmailTemplate(array $map, string $type, string $status): ?string
    {
        return $map[$type][$status]
            ?? $map[$type]['success']
            ?? $map[$type]['approved']
            ?? $map['default']
            ?? ($map[array_key_first($map)] ?? null);
    }

    /**
     * Push template fallback.
     */
    private function resolvePushTemplate(array $map, string $type): ?string
    {
        return $map[$type] ?? $map['default'] ?? null;
    }

    /**
     * Get admin and staff emails.
     */
    private function getAdminEmails(int $userId): array
    {
        $siteEmails = parseEmails(setting('site_email', 'global'));
        $staffEmails = getAttachedStaffAdminEmails($userId);
        
        // Convert Collection to array if needed
        $staffEmailsArray = $staffEmails instanceof \Illuminate\Support\Collection 
            ? $staffEmails->toArray() 
            : (array) $staffEmails;
        
        $allEmails = array_merge($siteEmails, $staffEmailsArray);
        $uniqueEmails = array_values(array_unique(array_filter($allEmails)));
        
        Log::debug('Admin emails retrieved', [
            'user_id' => $userId,
            'site_emails' => $siteEmails,
            'staff_emails' => $staffEmailsArray,
            'total_unique' => count($uniqueEmails),
            'all_emails' => $uniqueEmails,
        ]);
        
        return $uniqueEmails;
    }

    /**
     * Parse CSV or array of emails.
     * Supports comma-separated emails only.
     * Uses global parseEmails() helper function.
     */
    private function parseEmails(mixed $value): array
    {
        return \parseEmails($value);
    }

    private function isValidEmail(?string $email): bool
    {
        return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function logSkip(int $txnId, string $reason): void
    {
        Log::warning("Notification skipped: {$reason}", ['transaction_id' => $txnId]);
    }

    private function logError(\Throwable $e, int $txnId, ?string $status = null): void
    {
        Log::error('Transaction notification failed', [
            'transaction_id' => $txnId,
            'status'         => $status,
            'error'          => $e->getMessage(),
            'trace'          => $e->getTraceAsString(),
        ]);
    }
}
