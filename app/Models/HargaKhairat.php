<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaKhairat extends Model
{
    protected $table = 'hargakhairat';

    protected $fillable = [
        'masjid_id',
        'bayaran_tahunan',
        'yuran_pendaftaran',
        'sumbangan_seorang',
    ];

    public function masjid()
    {
        return $this->belongsTo(Masjid::class, 'masjid_id');
    }
}
