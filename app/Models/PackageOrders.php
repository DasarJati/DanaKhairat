<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageOrders extends Model
{
    use HasFactory;

    protected $table = 'package_orders';
    public $timestamps = true; // ada created_at dan updated_at

    protected $fillable = [
        'user_id',
        'masjid_id',
        'package_id',
        'amount',
        'receipt_path',
        'payment_type',
        'status',
        'approved_by',
    ];

    

    // ===== RELATIONS =====

    public function ajk()
    {
        return $this->belongsTo(Ajks::class, 'ajk_id', 'id');
    }

    public function masjid()
    {
        return $this->belongsTo(Masjid::class, 'masjid_id', 'id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }


}
