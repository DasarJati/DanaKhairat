<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRegister extends Model
{
    protected $table = 'user_register';

    // Laravel secara default mengurus created_at & updated_at
    public $timestamps = true;

    protected $fillable = [
        /* ===== USER INFO ===== */
        'nama',
        'ic_number',
        'tarikh_lahir',
        'umur',
        'jantina',
        'bangsa',
        'statususer',
        'alamat',
        'telefon_bimbit',
        'amount',
        'email',
        'password',
        'masjid_id',
        'agree_terms',
        'receipt_path',
        'ahli_type',

        /* ===== STATUS PENDAFTARAN ===== */
        'approval_status',

        /* ===== WARIS ===== */
        'waris_nama',
        'waris_ic',
        'waris_alamat',
        'waris_telefon_pejabat',
        'waris_telefon_bimbit',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'agree_terms' => 'boolean',
        'tarikh_lahir' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
