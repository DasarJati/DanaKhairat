<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AjkPakej extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'ajk_pakej';
    
    protected $fillable = [
    'ajk_id',
    'price',
    'resit',
];


    /**
     * Relationship dengan AJK
     */
    public function ajk()
    {
        return $this->belongsTo(AjkRegister::class, 'ajk_id');
    }
}