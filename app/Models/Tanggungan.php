<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tanggungan extends Model
{
    use HasFactory;

    protected $table = 'tanggungan'; // nama table kau

    protected $fillable = [
        'ahli_id',
        'nama',
        'ic_number',
        'hubungan',
        'jantina',
        'oku',
        'family_id',
        'status',
        'document_path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tuntutan()
    {
        return $this->hasMany(
            TuntutanKhairat::class,
            'tanggungan_id', // ✅ FK sebenar dalam table tuntutan_khairat
            'id'
        );
    }

    public function ahliKariah()
    {
        
        return $this->belongsTo(AhliKariah::class, 'ahli_id');
    }
}
