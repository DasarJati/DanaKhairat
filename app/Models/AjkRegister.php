<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AjkRegister extends Authenticatable
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'ajk_register';

    protected $fillable = [
        'nama_masjid',
        'kariah_id',
        'negeri_id',  // Pastikan ada ini
        'poskod_id',
        'type',
        'alamat',
        'alamat2',
        'bandar',
        'nama_pendaftar',
        'notel',
        'ic',
        'salinan_ic',
        'sijil_pendaftaran',
        'slip_akaun',
        'email',
        'password',
        'status',
        'trial_start',
        'trial_end'
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'trial_start' => 'datetime',
        'trial_end' => 'datetime'
    ];

    /**
     * Relationships 
     */

    public function package(): HasOne
    {
        return $this->hasOne(AjkPakej::class, 'ajk_id');
    }

    // 🔥 Tambahan relationships anda
    public function info(): HasOne
    {
        return $this->hasOne(AjkInfo::class, 'ajk_id');
    }

    public function khairat(): HasOne
    {
        return $this->hasOne(HargaKhairat::class, 'ajk_id');
    }

    public function senarai(): HasOne
    {
        return $this->hasOne(AjkList::class, 'ajk_id');
    }

    public function bank(): HasOne
    {
        return $this->hasOne(Bank::class, 'ajk_id');
    }
    

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 0);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 1);
    }

    public function scopeRejected($query) 
    {
        return $query->where('status', 2);
    }
}
