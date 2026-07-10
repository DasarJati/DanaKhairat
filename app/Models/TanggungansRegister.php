<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TanggungansRegister extends Model
{
    use HasFactory;

    protected $table = 'tanggungans_register'; 

    protected $fillable = [
        'user_register_id',
        'nama',
        'ic_number',
        'hubungan',
        'document_path',
        'oku',
    ];

    public function userRegister()
    {
        return $this->belongsTo(UserRegister::class, 'user_register_id');
    }

   
}