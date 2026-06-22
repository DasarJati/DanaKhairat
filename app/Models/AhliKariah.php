<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AhliKariah extends Model
{
    use HasFactory;

    protected $table = 'ahli_kariah';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'masjid_id',
        'nama',
        'email',
        'ic',
        'notel',
        'jantina',
        'alamat',
        'status',
        'family_id',
        'is_ketua'
    ];


    public function masjid()
    {
        return $this->belongsTo(masjid::class, 'masjid_id', 'id');
    }


    public function payments()
    {
        return $this->belongsTo(Payment::class, 'masjid_id', 'id');
    }

    public function tanggungan()
    {
        return $this->hasMany(Tanggungan::class, 'ahli_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(SubscriptionsKariah::class, 'user_id', 'user_id')
            ->where('masjid_id', $this->masjid_id);
    }

    public function activeSubscription()
    {
        return $this->hasOne(SubscriptionsKariah::class, 'user_id', 'user_id')
            ->where('masjid_id', $this->masjid_id)
            ->where('status', 'active')
            ->latest();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function waris()
    {
        return $this->hasMany(Waris::class, 'ahli_id');
    }

    public function tuntutan()
    {
        return $this->hasMany(TuntutanKhairat::class, 'ahli_id');
    }

    /**
     * Get current active subscription period for the user
     */
    public function getCurrentSubscriptionPeriod()
    {
        $activeSubscription = $this->activeSubscription;

        if (!$activeSubscription) {
            return null;
        }

        return (object) [
            'start_date' => $activeSubscription->start_date,
            'end_date' => $activeSubscription->end_date,
            'status' => $activeSubscription->status,
            'is_active' => $activeSubscription->status === 'active' && $activeSubscription->end_date >= now(),
            'days_remaining' => $activeSubscription->end_date ? now()->diffInDays($activeSubscription->end_date, false) : 0,
        ];
    }

    /**
     * Get all subscription history for the user
     */
    public function getSubscriptionHistory()
    {
        return $this->subscriptions()
            ->with('payment')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Check if user has active subscription
     */
    public function hasActiveSubscription()
    {
        $subscription = $this->activeSubscription;

        if (!$subscription) {
            return false;
        }

        return $subscription->status === 'active' && $subscription->end_date >= now();
    }

    /**
     * Get latest subscription end date
     */
    public function getLatestSubscriptionEndDate()
    {
        $latestSubscription = $this->subscriptions()
            ->orderBy('end_date', 'desc')
            ->first();

        return $latestSubscription ? $latestSubscription->end_date : null;
    }

    /**
     * Check if subscription is expiring soon (e.g., within 30 days)
     */
    public function isSubscriptionExpiringSoon($daysThreshold = 30)
    {
        $subscription = $this->activeSubscription;

        if (!$subscription || $subscription->end_date < now()) {
            return false;
        }

        $daysRemaining = now()->diffInDays($subscription->end_date);

        return $daysRemaining <= $daysThreshold;
    }
}
