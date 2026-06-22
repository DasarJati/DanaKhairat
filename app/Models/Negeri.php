<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Negeri extends Model
{
    protected $table = 'negeri';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        ];


}
