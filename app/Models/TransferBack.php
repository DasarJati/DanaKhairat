<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferBack extends Model
{
    protected $table = 'transfer_back';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'masjid_id',
        'name',
        'amount',
        'final_amount',
        'paid_by',
        'resit_path',

    ];

    protected $casts = [

        'amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function masjid()
    {
     return $this->belongsTo(Masjid::class);
    }


}