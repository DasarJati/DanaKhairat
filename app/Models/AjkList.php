<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AjkList extends Model {
    protected $table = 'ajk_list';
    protected $fillable = ['ajk_id',
                            'nama',
                            'jawatan',
                            'telefon'];
}
