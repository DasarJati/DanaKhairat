<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AjkInfo extends Model {
    protected $table = 'hargakhairat';
    protected $fillable = ['ajk_id','telefon','email'];
}
