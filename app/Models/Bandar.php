<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bandar extends Model
{
    protected $table = 'bandar';
    public $timestamps = false;

    protected $fillable = ['negeri_id', 'nama'];

    public function negeri()
    {
        return $this->belongsTo(Negeri::class);
    }
}
