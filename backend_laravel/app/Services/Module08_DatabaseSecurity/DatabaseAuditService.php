<?php

namespace App\Services\Module08_DatabaseSecurity;

use App\Models\Module08_DatabaseSecurity\DatabaseAudit;
use Illuminate\Support\Facades\DB;

class DatabaseAuditService
{
    public function logQuery($table, $action, $query, $userId = null)
    {
        return DatabaseAudit::create([
            'database_name' => DB::getDatabaseName(),
            'table_name' => $table,
            'action' => $action,
            'user' => $userId ?? auth()->id(),
            'query' => $query,
            'affected_rows' => DB::affectingStatement($query),
            'ip_address' => request()->ip(),
            'executed_at' => now(),
            'details' => [
                'user_agent' => request()->userAgent(),
                'request_url' => request()->fullUrl()
            ]
        ]);
    }

    public function getAuditTrail($table = null, $userId = null, $from = null, $to = null)
    {
        $query = DatabaseAudit::query();
        
        if ($table) {
            $query->where('table_name', $table);
        }
        
        if ($userId) {
            $query->where('user', $userId);
        }
        
        if ($from) {
            $query->where('executed_at', '>=', $from);
        }
        
        if ($to) {
            $query->where('executed_at', '<=', $to);
        }
        
        return $query->orderBy('executed_at', 'desc')->get();
    }

    public function getTableActivitySummary($days = 7)
    {
        return DatabaseAudit::where('executed_at', '>=', now()->subDays($days))
            ->select('table_name', 'action', DB::raw('COUNT(*) as count'))
            ->groupBy('table_name', 'action')
            ->get();
    }
}