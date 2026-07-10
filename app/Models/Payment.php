<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'user_id',
        'masjid_id',
        'amount',
        'payment_method',
        'status',
        'receipt_path',
        'remarks',
        'type',
        'flow_type',
        'paid_at',
        'bill_code',
        'reference_type',
        'reference_id',
    ];

    protected $casts = [
        'paid_at'   => 'datetime',
        'created_at' => 'datetime',
    ];

    public function getNetAmountAttribute()
    {
        return max(0, $this->amount - 10);
    }

    // 🔗 Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function masjid()
    {
        return $this->belongsTo(masjid::class, 'masjid_id', 'id');
    }

    public function ahliKariah()
    {
        return $this->hasMany(AhliKariah::class, 'masjid_id', 'id');
    }

    public function transferBack()
    {
        return $this->hasOne(TransferBack::class);
    }

    public function subscription()
    {
        return $this->hasOne(SubscriptionsKariah::class, 'payment_id', 'id');
    }
}
