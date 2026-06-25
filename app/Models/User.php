<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $timestamps = false;

    protected $table = 'users';

    protected $fillable = [
        'nama',
        'ic_number',
        'email',
        'password',
        'masjid_id',
        'role',
        'status',
        'tel_number'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'tarikh_lahir' => 'date',
        'agree_terms' => 'boolean',
    ];

    protected $dates = [
        'membership_start',
        'membership_end',
    ];

    // ========== ADD THIS RELATIONSHIP ==========
    /**
     * Get subscriptions based on user role
     * Role 1 (AJK) -> SubscriptionsMasjid
     * Role 2 (Ahli) -> SubscriptionsKariah
     */
    public function subscriptions()
    {
        if ($this->role == 1) {
            return $this->hasMany(SubscriptionsMasjid::class, 'user_id', 'id');
        } elseif ($this->role == 2) {
            return $this->hasMany(SubscriptionsKariah::class, 'user_id', 'id');
        }

        // Default fallback
        return $this->hasMany(SubscriptionsKariah::class, 'user_id', 'id');
    }

    // ========== ADD THIS HELPER METHOD ==========
    /**
     * Get active subscription for the user based on their role
     */
    public function getActiveSubscription()
    {
        $now = now();

        if ($this->role == 1) {
            // AJK - Check SubscriptionsMasjid
            return SubscriptionsMasjid::where('user_id', $this->id)
                ->where('masjid_id', $this->masjid_id)
                ->where('status', 'active')
                ->where('start_date', '<=', $now)
                ->where('end_date', '>=', $now)
                ->latest()
                ->first();
        } elseif ($this->role == 2) {
            // Ahli Khairat - Check SubscriptionsKariah
            return SubscriptionsKariah::where('user_id', $this->id)
                ->where('masjid_id', $this->masjid_id)
                ->where('status', 'active')
                ->where('start_date', '<=', $now)
                ->where('end_date', '>=', $now)
                ->latest()
                ->first();
        }

        return null;
    }

    /**
     * Check if user has an active subscription
     */
    public function isSubscriptionActive()
    {
        return $this->getActiveSubscription() !== null;
    }

    
    public function waris()
    {
        return $this->hasMany(Waris::class, 'ahli_id');
    }

    public function tanggungan()
    {
        return $this->hasMany(Tanggungan::class, 'ahli_id');
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class, 'user_id');
    }

    public function tuntutan()
    {
        return $this->hasMany(TuntutanKhairat::class, 'user_id');
    }

    public function masjidDetail()
    {
        return $this->belongsTo(masjid::class, 'masjid_id', 'id');
    }

    public function masjid()
    {
        return $this->belongsTo(masjid::class);
    }

    public function ahliKariah()
    {
        return $this->hasOne(AhliKariah::class);
    }

    /**
 * Get the latest subscription for the user
 */
public function getLatestSubscription()
{
    return $this->subscriptions()
        ->orderBy('end_date', 'desc')
        ->orderBy('created_at', 'desc')
        ->first();
}

/**
 * Get the latest ACTIVE subscription for the user
 */
public function getLatestActiveSubscription()
{
    $now = now();
    
    return $this->subscriptions()
        ->where('status', 'active')
        ->where('start_date', '<=', $now)
        ->where('end_date', '>=', $now)
        ->orderBy('end_date', 'desc')
        ->orderBy('created_at', 'desc')
        ->first();
}

/**
 * Get subscription end date (latest subscription)
 */
public function getSubscriptionEndDate()
{
    $latest = $this->getLatestSubscription();
    return $latest ? $latest->end_date : null;
}

/**
 * Check if user has an active subscription using latest subscription
 */
public function hasActiveSubscription()
{
    $latestSubscription = $this->getLatestSubscription();
    
    if (!$latestSubscription) {
        return false;
    }
    
    return $latestSubscription->status === 'active' &&
        $latestSubscription->end_date &&
        now()->lte(Carbon::parse($latestSubscription->end_date));
}

// Add these methods to your User.php model

/**
 * Get the family ID of the user
 */
public function getFamilyId()
{
    $ahliKariah = $this->ahliKariah;
    return $ahliKariah ? $ahliKariah->family_id : null;
}

/**
 * Get total family members count (including ketua from ahli_kariah and tanggungan)
 */
public function getTotalFamilyMembers()
{
    $familyId = $this->getFamilyId();
    
    if ($familyId) {
        // Count ketua in ahli_kariah table
        $ketuaCount = AhliKariah::where('family_id', $familyId)
            ->where('is_ketua', 1)
            ->count();
        
        // Count tanggungan from tanggungan table
        $tanggunganCount = Tanggungan::where('family_id', $familyId)->count();
        
        return $ketuaCount + $tanggunganCount;
    }
    
    // Fallback to current user's tanggungan
    return 1 + ($this->tanggungan ? $this->tanggungan->count() : 0);
}

/**
 * Get ketua count for the family
 */
public function getKetuaCount()
{
    $familyId = $this->getFamilyId();
    
    if ($familyId) {
        return AhliKariah::where('family_id', $familyId)
            ->where('is_ketua', 1)
            ->count();
    }
    
    return 1; // Current user is ketua
}

/**
 * Get tanggungan count for the family
 */
public function getTanggunganCount()
{
    $familyId = $this->getFamilyId();
    
    if ($familyId) {
        return Tanggungan::where('family_id', $familyId)->count();
    }
    
    return $this->tanggungan ? $this->tanggungan->count() : 0;
}

/**
 * Get all expired subscriptions for the user
 */
public function getExpiredSubscriptions()
{
    $now = Carbon::now();
    
    if ($this->role == 1) {
        return SubscriptionsMasjid::where('user_id', $this->id)
            ->where('status', 'active')
            ->whereDate('end_date', '<', $now)
            ->get();
    } elseif ($this->role == 2) {
        return SubscriptionsKariah::where('user_id', $this->id)
            ->where('status', 'active')
            ->whereDate('end_date', '<', $now)
            ->get();
    }
    
    return collect();
}

/**
 * Check if user has expired subscriptions
 */
public function hasExpiredSubscriptions()
{
    return $this->getExpiredSubscriptions()->count() > 0;
}

/**
 * Get the latest expired subscription
 */
public function getLatestExpiredSubscription()
{
    $subscriptions = $this->getExpiredSubscriptions();
    return $subscriptions->sortByDesc('end_date')->first();
}
}
