<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'entity_type',
        'description',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'additional_data',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'additional_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope a query to only include logs by entity type
     */
    public function scopeByEntity($query, $entityType)
    {
        return $query->where('entity_type', $entityType);
    }

    /**
     * Scope a query to only include logs by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include recent logs
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope a query to only include today's logs
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope a query to only include logs within date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include logs with specific IP
     */
    public function scopeByIp($query, $ip)
    {
        return $query->where('ip_address', $ip);
    }

    // ==========================================
    // ACCESSORS
    // ==========================================

    /**
     * Get formatted entity type label
     */
    public function getEntityTypeLabelAttribute()
    {
        $labels = [
            'login' => 'Log Masuk',
            'logout' => 'Log Keluar',
            'failed_login' => 'Log Masuk Gagal',
            'password_reset' => 'Reset Kata Laluan',
            'password_change' => 'Tukar Kata Laluan',
            'registration' => 'Pendaftaran',
            'profile_update' => 'Kemaskini Profil',
            'subscription' => 'Langganan',
            'payment' => 'Pembayaran',
            'admin_action' => 'Tindakan Admin',
            'system' => 'Sistem',
            'error' => 'Ralat',
            'death_record' => 'Rekod Kematian',
            'tuntutan' => 'Tuntutan',
            'transfer' => 'Pindahan',
            'approval' => 'Kelulusan',
            'renewal' => 'Pembaharuan',
        ];

        return $labels[$this->entity_type] ?? ucfirst(str_replace('_', ' ', $this->entity_type));
    }

    /**
     * Get user name (with fallback)
     */
    public function getUserNameAttribute()
    {
        return $this->user ? $this->user->nama : 'Sistem / Unknown';
    }

    /**
     * Get user email
     */
    public function getUserEmailAttribute()
    {
        return $this->user ? $this->user->email : null;
    }

    /**
     * Get formatted description with time and user
     */
    public function getFormattedDescriptionAttribute()
    {
        $time = $this->created_at->format('d/m/Y H:i:s');
        $user = $this->user_name;
        return "[{$time}] {$user}: {$this->description}";
    }

    /**
     * Get short description (truncated)
     */
    public function getShortDescriptionAttribute($length = 50)
    {
        return strlen($this->description) > $length 
            ? substr($this->description, 0, $length) . '...' 
            : $this->description;
    }

    /**
     * Get icon based on entity type
     */
    public function getIconAttribute()
    {
        $icons = [
            'login' => 'fa-sign-in-alt',
            'logout' => 'fa-sign-out-alt',
            'failed_login' => 'fa-times-circle',
            'password_reset' => 'fa-key',
            'password_change' => 'fa-key',
            'registration' => 'fa-user-plus',
            'profile_update' => 'fa-user-edit',
            'subscription' => 'fa-crown',
            'payment' => 'fa-money-bill-wave',
            'admin_action' => 'fa-shield-alt',
            'system' => 'fa-cog',
            'error' => 'fa-exclamation-triangle',
            'death_record' => 'fa-cross',
            'tuntutan' => 'fa-file-invoice',
            'transfer' => 'fa-exchange-alt',
            'approval' => 'fa-check-circle',
            'renewal' => 'fa-sync-alt',
        ];

        return $icons[$this->entity_type] ?? 'fa-circle';
    }

    /**
     * Get color based on entity type
     */
    public function getColorAttribute()
    {
        $colors = [
            'login' => 'text-emerald-500',
            'logout' => 'text-red-500',
            'failed_login' => 'text-red-600',
            'password_reset' => 'text-amber-500',
            'password_change' => 'text-amber-500',
            'registration' => 'text-blue-500',
            'profile_update' => 'text-indigo-500',
            'subscription' => 'text-purple-500',
            'payment' => 'text-emerald-500',
            'admin_action' => 'text-blue-600',
            'system' => 'text-gray-500',
            'error' => 'text-red-600',
            'death_record' => 'text-gray-700',
            'tuntutan' => 'text-orange-500',
            'transfer' => 'text-cyan-500',
            'approval' => 'text-green-500',
            'renewal' => 'text-teal-500',
        ];

        return $colors[$this->entity_type] ?? 'text-gray-400';
    }

    /**
     * Get badge color for entity type
     */
    public function getBadgeColorAttribute()
    {
        $badges = [
            'login' => 'bg-emerald-100 text-emerald-700',
            'logout' => 'bg-red-100 text-red-700',
            'failed_login' => 'bg-red-100 text-red-700',
            'password_reset' => 'bg-amber-100 text-amber-700',
            'password_change' => 'bg-amber-100 text-amber-700',
            'registration' => 'bg-blue-100 text-blue-700',
            'profile_update' => 'bg-indigo-100 text-indigo-700',
            'subscription' => 'bg-purple-100 text-purple-700',
            'payment' => 'bg-emerald-100 text-emerald-700',
            'admin_action' => 'bg-blue-100 text-blue-700',
            'system' => 'bg-gray-100 text-gray-700',
            'error' => 'bg-red-100 text-red-700',
            'death_record' => 'bg-gray-100 text-gray-700',
            'tuntutan' => 'bg-orange-100 text-orange-700',
            'transfer' => 'bg-cyan-100 text-cyan-700',
            'approval' => 'bg-green-100 text-green-700',
            'renewal' => 'bg-teal-100 text-teal-700',
        ];

        return $badges[$this->entity_type] ?? 'bg-gray-100 text-gray-700';
    }

    // ==========================================
    // MUTATORS
    // ==========================================

    /**
     * Automatically set IP address when creating
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->ip_address)) {
                $model->ip_address = Request::ip();
            }
            if (empty($model->user_agent)) {
                $model->user_agent = Request::userAgent();
            }
            if (empty($model->url)) {
                $model->url = Request::fullUrl();
            }
            if (empty($model->method)) {
                $model->method = Request::method();
            }
        });
    }

    // ==========================================
    // HELPER METHODS
    // ==========================================

    /**
     * Check if log is related to a specific user
     */
    public function isForUser($userId)
    {
        return $this->user_id == $userId;
    }

    /**
     * Check if log is of a specific type
     */
    public function isType($entityType)
    {
        return $this->entity_type == $entityType;
    }

    /**
     * Get additional data as array
     */
    public function getAdditionalData()
    {
        return $this->additional_data ?? [];
    }

    /**
     * Get specific key from additional data
     */
    public function getAdditionalDataKey($key, $default = null)
    {
        $data = $this->getAdditionalData();
        return $data[$key] ?? $default;
    }

    // ==========================================
    // STATISTICS METHODS
    // ==========================================

    /**
     * Get total count by entity type
     */
    public static function countByEntity($entityType)
    {
        return self::where('entity_type', $entityType)->count();
    }

    /**
     * Get count for today
     */
    public static function countToday()
    {
        return self::whereDate('created_at', today())->count();
    }

    /**
     * Get count for specific user
     */
    public static function countByUser($userId)
    {
        return self::where('user_id', $userId)->count();
    }

    /**
     * Get all entity types with counts
     */
    public static function getEntityCounts()
    {
        return self::select('entity_type')
            ->selectRaw('count(*) as total')
            ->groupBy('entity_type')
            ->orderBy('total', 'desc')
            ->get();
    }

    /**
     * Get top users by activity
     */
    public static function getTopUsers($limit = 10)
    {
        return self::select('user_id')
            ->selectRaw('count(*) as total')
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->limit($limit)
            ->with('user')
            ->get();
    }

    /**
     * Get activity summary for dashboard
     */
    public static function getSummary()
    {
        return [
            'total' => self::count(),
            'today' => self::countToday(),
            'week' => self::where('created_at', '>=', now()->subDays(7))->count(),
            'month' => self::where('created_at', '>=', now()->subDays(30))->count(),
            'by_entity' => self::getEntityCounts(),
            'top_users' => self::getTopUsers(5),
        ];
    }

    /**
     * Clean old logs
     */
    public static function cleanOldLogs($days = 90)
    {
        return self::where('created_at', '<', now()->subDays($days))->delete();
    }
}