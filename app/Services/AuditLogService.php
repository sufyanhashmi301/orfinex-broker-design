<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuditLogService
{
    /**
     * Log an audit event
     *
     * @param array $data
     * @return AuditLog|null
     */
    public function log(array $data): ?AuditLog
    {
        try {
            return AuditLog::create([
                'action' => $data['action'],
                'model' => $data['model'] ?? null,
                'model_id' => $data['model_id'] ?? null,
                'admin_id' => $data['admin_id'] ?? Auth::id(),
                'changes' => json_encode($data['changes'] ?? []),
                'ip_address' => $data['ip_address'] ?? request()->ip(),
                'user_agent' => $data['user_agent'] ?? request()->userAgent(),
                'created_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create audit log', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            return null;
        }
    }

    /**
     * Get audit history for a model
     *
     * @param array $filters
     * @return array
     */
    public function getHistory(array $filters): array
    {
        $query = AuditLog::query();

        if (isset($filters['model'])) {
            $query->where('model', $filters['model']);
        }

        if (isset($filters['model_id'])) {
            $query->where('model_id', $filters['model_id']);
        }

        if (isset($filters['actions'])) {
            $query->whereIn('action', $filters['actions']);
        }

        if (isset($filters['admin_id'])) {
            $query->where('admin_id', $filters['admin_id']);
        }

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')
            ->with('admin:id,name,email')
            ->get()
            ->toArray();
    }

    /**
     * Get audit statistics
     *
     * @param array $filters
     * @return array
     */
    public function getStatistics(array $filters = []): array
    {
        $query = AuditLog::query();

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        return [
            'total_actions' => $query->count(),
            'unique_admins' => $query->distinct('admin_id')->count(),
            'actions_by_type' => $query->groupBy('action')
                ->selectRaw('action, count(*) as count')
                ->pluck('count', 'action')
                ->toArray(),
            'actions_by_day' => $query->groupBy(\DB::raw('DATE(created_at)'))
                ->selectRaw('DATE(created_at) as date, count(*) as count')
                ->orderBy('date', 'desc')
                ->limit(30)
                ->pluck('count', 'date')
                ->toArray()
        ];
    }

    /**
     * Clean old audit logs (retention policy)
     *
     * @param int $daysToKeep
     * @return int Number of deleted records
     */
    public function cleanOldLogs(int $daysToKeep = 365): int
    {
        $cutoffDate = now()->subDays($daysToKeep);
        
        return AuditLog::where('created_at', '<', $cutoffDate)->delete();
    }
}
