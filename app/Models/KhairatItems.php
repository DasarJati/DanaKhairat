<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhairatItems extends Model
{
    use HasFactory;

    protected $table = 'khairat_items'; 

    protected $fillable = [
        'tuntutan_id',
        'item_name',
        'item_label',
        'description',
        'amount'
     
    ];

    public function tuntutankhairat()
    {
        return $this->belongsTo(TuntutanKhairat::class);
    }
}
