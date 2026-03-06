<?php
namespace App\Services;

use App\Models\User;
use App\Models\HistoryLog;

class HistoryLogService
{
    public static function log($userId, $action, $module, $status, $ip)
    {
        HistoryLog::create([
            'user_id' => $userId,
            'action' => $action,
            'module' => $module,
            'status' => $status,
            'ip_address' => $ip,
        ]);
    }
}
?>