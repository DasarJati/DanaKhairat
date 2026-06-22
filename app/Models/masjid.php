<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Masjid extends Model
{
    protected $table = 'masjids';

    public $timestamps = false;

    protected $fillable = [
        'kariah_id',
        'negeri_id',
        'poskod_id',
        'nama',
        'slug',
        'type',
        'alamat',
        'bandar',
        'status',
        'image_path',
        'salinan_ic',
        'sijil_pendaftaran',
        'slip_akaun',
    ];

    // =====================
    // RELATIONSHIPS
    // =====================

    // Masjid → ramai user (AJK + Ahli Khairat)
    public function users()
    {
        return $this->hasMany(User::class, 'masjid_id', 'id');
    }

    // Masjid → satu harga khairat
    public function hargaKhairat()
    {
        return $this->hasOne(HargaKhairat::class, 'masjid_id', 'id');
    }

    // Masjid → satu bank (atau tukar hasMany kalau nak banyak bank)
    public function bank()
    {
        return $this->hasOne(Bank::class, 'masjid_id', 'id');
    }

    public function ahliKariah()
    {
        return $this->hasMany(AhliKariah::class, 'masjid_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'masjid_id', 'id');
    }

    public function transferBacks()
    {
        return $this->hasMany(TransferBack::class);
    }

    // Masjid → satu kariah
    public function kariah()
    {
        return $this->belongsTo(Kariah::class, 'kariah_id', 'id');
    }

    // Masjid → satu negeri
    public function negeri()
    {
        return $this->belongsTo(Negeri::class, 'negeri_id', 'id');
    }

    // Masjid → satu poskod
    public function poskod()
    {
        return $this->belongsTo(Poskod::class, 'poskod_id', 'id');
    }

    public function getSlugAttribute()
    {
        $slug = strtolower($this->nama);
        $slug = preg_replace('/[^a-z0-9]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }
}
