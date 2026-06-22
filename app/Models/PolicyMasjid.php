<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PolicyMasjid extends Model
{
    use HasFactory;

    protected $table = 'policy_masjid'; // Make sure this matches your table name
    public $timestamps = true; // Set to true if you have created_at/updated_at columns

    protected $fillable = [
        'policy_id',
        'title',
        'description',
    ];

    // If your primary key is not 'id', define it
    protected $primaryKey = 'id';

    public function header()
    {
        return $this->belongsTo(PolicyHeader::class, 'policy_id');
    }
}
