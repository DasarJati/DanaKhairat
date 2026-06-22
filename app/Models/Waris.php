<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waris extends Model
{
    use HasFactory;

    protected $table = 'waris'; // nama table dalam DB

    protected $fillable = [
        'ahli_id',
        'nama',
        'ic_number',
        'alamat',
        'telefon_pejabat',
        'telefon_bimbit',
    ];

    public function user()
    {
        return $this->belongsTo(AhliKariah::class);
    }
}
