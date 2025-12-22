<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Admin;

class ActivityLogService
{
    /**
     * Log activity for user or admin.
     *
     * @param string $action
     * @param string|null $description
     * @param array $meta Optional structured JSON data
     * @return void
     */
    public static function log(string $action, ?string $description = null, array $meta = []): void
    {
        $actor = Auth::user() ?? Auth::guard('admin')->user();

        $allowedUnauthenticatedActions = [
            'password_reset',
            'get_password',
            'password_reset_request',
            'user_register',
        ];

        if (!$actor && in_array($action, $allowedUnauthenticatedActions)) {
            $email = $meta['email'] ?? request('email');
            if ($email) {
                $actor = User::where('email', $email)->first();
            }
        }

        try {
            ActivityLog::create([
                'actor_id'   => $actor->id,
                'actor_type' => get_class($actor),
                'action'     => $action,
                'description'=> $description,
                'meta'       => $meta,
                'ip'         => request()->ip(),
                'location'   => getLocation()->name,
                'agent'      => request()->userAgent(),
            ]);

        } catch (\Throwable $e) {
            Log::error("ActivityLogService failed: " . $e->getMessage());
        }
    }
}
