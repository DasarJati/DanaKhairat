<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionsMasjid extends Model
{
    use HasFactory;

    protected $table = 'subscriptions_masjid';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'masjid_id',
        'package_id',
        'package_order_id',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // ===== RELATIONS =====

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function masjid()
    {
        return $this->belongsTo(Masjid::class, 'masjid_id', 'id');
    }
    
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }

    public function packageOrder()
    {
        return $this->belongsTo(PackageOrders::class, 'package_order_id', 'id');
    }

}
