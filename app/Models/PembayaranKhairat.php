<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranKhairat extends Model
{
    protected $table = 'pembayaran_khairat';

    protected $casts = [
    'tarikh_kelulusan' => 'date',
    'dibayar_pada'     => 'datetime',
];

    protected $dates = ['dibayar_pada'];

    protected $fillable = [
        'tuntutan_id',
        'tarikh_kelulusan',
        'jumlah_bayar',
        'resit_bayaran',
        'status',
        'dibayar_oleh',
        'dibayar_pada'
    ];

    public function tuntutan()
{
    return $this->belongsTo(TuntutanKhairat::class, 'tuntutan_id');
}

}

