<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;

    public $timestamps = false; 
    protected $table = 'locations';

    // Benarkan mass assignment untuk field ini
    protected $fillable = [
        'poskod',
        'bandar',
        'negeri'
    ];

    /**
     * Relationship: Satu lokasi boleh ada banyak pendaftaran AJK
     */
    public function registrations()
    {
        return $this->hasMany(AjkRegister::class, 'poskod', 'poskod');
    }
}