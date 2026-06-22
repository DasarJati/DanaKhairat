<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'pakej';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'limit_kariah',
        'price',
        'renewal_price',
        'duration'

        ];


}
