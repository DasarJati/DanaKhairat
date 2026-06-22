<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionsKariah extends Model
{
    use HasFactory;

    protected $table = 'subscriptions_kariah';
    public $timestamps = true; // ada created_at dan updated_at

    protected $fillable = [
        'user_id',
        'masjid_id',
        'start_date',
        'end_date',
        'status',
        'price',
        'payment_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // ===== RELATIONS =====



    public function masjid()
    {
        return $this->belongsTo(Masjid::class, 'masjid_id', 'id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
