<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Ajks extends Authenticatable
{
    use HasFactory;

    protected $table = 'ajks';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'masjid_id',
        'position',
        'ic',
        'notel',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function masjid()
    {
        return $this->belongsTo(Masjid::class, 'masjid_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

