<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Poskod extends Model
{
    use HasFactory;

    protected $table = 'poskod'; // Make sure this matches your table name
    public $timestamps = false; // Set to true if you have created_at/updated_at columns

    protected $fillable = [
        'poskod_num',
        'nama',
        'negeri_id'
    ];

    // If your primary key is not 'id', define it
    protected $primaryKey = 'id';
}