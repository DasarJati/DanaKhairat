<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kariah extends Model
{
    use HasFactory;

    protected $table = 'kariahs';

    protected $fillable = [
        'nama',
        'poskod_id',
        'bandar'
        
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function poskod()
{
    return $this->belongsTo(Poskod::class, 'poskod_id');
}

   
}