<?php
// app/Helpers/ActivityLogHelper.php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;

class ActivityLogHelper
{
    /**
     * Log an activity
     */
    public static function log($entityType, $description, $userId = null, $additionalData = [])
    {
        try {
            $log = ActivityLog::create([
                'user_id' => $userId ?? Auth::id(),
                'entity_type' => $entityType,
                'description' => $description,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'url' => Request::fullUrl(),
                'method' => Request::method(),
                'additional_data' => !empty($additionalData) ? $additionalData : null,
            ]);

            Log::info("Activity Log: {$entityType} - {$description}", [
                'user_id' => $userId ?? Auth::id(),
                'ip' => Request::ip()
            ]);

            return $log;

        } catch (\Exception $e) {
            Log::error('Failed to log activity: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Log login activity
     */
    public static function logLogin($userId = null)
    {
        $user = $userId ? \App\Models\User::find($userId) : Auth::user();
        return self::log(
            'login',
            'Log masuk ke sistem',
            $userId,
            [
                'user_name' => $user->nama ?? null,
                'user_email' => $user->email ?? null,
                'user_role' => $user->role ?? null,
                'masjid_id' => $user->masjid_id ?? null,
            ]
        );
    }

    /**
     * Log logout activity
     */
    public static function logLogout($userId = null)
    {
        $user = $userId ? \App\Models\User::find($userId) : Auth::user();
        return self::log(
            'logout',
            'Log keluar dari sistem',
            $userId,
            [
                'user_name' => $user->nama ?? null,
                'user_email' => $user->email ?? null,
            ]
        );
    }

    /**
     * Log failed login attempt
     */
    public static function logFailedLogin($email, $loginType = null)
    {
        return self::log(
            'failed_login',
            'Percubaan log masuk gagal',
            null,
            [
                'email' => $email,
                'login_type' => $loginType,
                'reason' => 'Invalid credentials'
            ]
        );
    }

    /**
     * Log password reset
     */
    public static function logPasswordReset($email, $type = 'user')
    {
        return self::log(
            'password_reset',
            'Permintaan reset password',
            null,
            [
                'email' => $email,
                'type' => $type
            ]
        );
    }

    /**
     * Log password change
     */
    public static function logPasswordChange($userId)
    {
        return self::log(
            'password_change',
            'Kata laluan telah ditukar',
            $userId
        );
    }

    /**
     * Log subscription update
     */
    public static function logSubscription($userId, $action, $subscriptionData = [])
    {
        return self::log(
            'subscription',
            $action,
            $userId,
            $subscriptionData
        );
    }

    /**
     * Log payment transaction
     */
    public static function logPayment($userId, $action, $paymentData = [])
    {
        return self::log(
            'payment',
            $action,
            $userId,
            $paymentData
        );
    }

    /**
     * Log user registration
     */
    public static function logRegistration($userId, $userData = [])
    {
        return self::log(
            'registration',
            'Pendaftaran pengguna baru',
            $userId,
            $userData
        );
    }

    /**
     * Log profile update
     */
    public static function logProfileUpdate($userId, $fields = [])
    {
        return self::log(
            'profile_update',
            'Kemaskini profil pengguna',
            $userId,
            ['updated_fields' => $fields]
        );
    }

    /**
     * Log error/exception
     */
    public static function logError($message, $context = [])
    {
        return self::log(
            'error',
            $message,
            Auth::id(),
            ['context' => $context]
        );
    }

    /**
     * Log admin action
     */
    public static function logAdminAction($action, $targetType, $targetId = null, $details = [])
    {
        return self::log(
            'admin_action',
            $action,
            Auth::id(),
            [
                'target_type' => $targetType,
                'target_id' => $targetId,
                'details' => $details
            ]
        );
    }

    /**
     * Log system activity
     */
    public static function logSystem($description, $data = [])
    {
        return self::log(
            'system',
            $description,
            null,
            $data
        );
    }

    /**
     * Log death record activity
     */
    public static function logDeathRecord($userId, $action, $data = [])
    {
        return self::log(
            'death_record',
            $action,
            $userId,
            $data
        );
    }

    /**
     * Log tuntutan activity
     */
    public static function logTuntutan($userId, $action, $data = [])
    {
        return self::log(
            'tuntutan',
            $action,
            $userId,
            $data
        );
    }

    /**
     * Log approval activity
     */
    public static function logApproval($userId, $action, $data = [])
    {
        return self::log(
            'approval',
            $action,
            $userId,
            $data
        );
    }

    /**
     * Log renewal activity
     */
    public static function logRenewal($userId, $action, $data = [])
    {
        return self::log(
            'renewal',
            $action,
            $userId,
            $data
        );
    }

    /**
     * Get activity logs for a user
     */
    public static function getUserLogs($userId, $limit = 50)
    {
        return ActivityLog::byUser($userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get activity logs by entity type
     */
    public static function getLogsByEntity($entityType, $limit = 50)
    {
        return ActivityLog::byEntity($entityType)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent activity logs
     */
    public static function getRecentLogs($limit = 100)
    {
        return ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get today's logs
     */
    public static function getTodayLogs()
    {
        return ActivityLog::today()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get logs by date range
     */
    public static function getLogsByDateRange($startDate, $endDate)
    {
        return ActivityLog::dateRange($startDate, $endDate)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Clear old logs
     */
    public static function clearOldLogs($days = 90)
    {
        return ActivityLog::cleanOldLogs($days);
    }

    /**
     * Get statistics
     */
    public static function getStatistics()
    {
        return ActivityLog::getSummary();
    }

    /**
     * Export logs to CSV
     */
    public static function exportToCSV($logs)
    {
        $filename = 'activity_logs_' . now()->format('Ymd_His') . '.csv';
        $handle = fopen('php://output', 'w');

        // Headers
        fputcsv($handle, [
            'ID', 'User', 'Entity Type', 'Description', 'IP Address',
            'URL', 'Method', 'Created At', 'Additional Data'
        ]);

        foreach ($logs as $log) {
            fputcsv($handle, [
                $log->id,
                $log->user_name,
                $log->entity_type_label,
                $log->description,
                $log->ip_address,
                $log->url,
                $log->method,
                $log->created_at->format('d/m/Y H:i:s'),
                json_encode($log->additional_data)
            ]);
        }

        fclose($handle);

        return $filename;
    }
}