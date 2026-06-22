<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PolicyHeader extends Model
{
    use HasFactory;

    protected $table = 'policy_header'; // Make sure this matches your table name
    public $timestamps = true; // Set to true if you have created_at/updated_at columns

    protected $fillable = [
        'masjid_id',
        'big_title'
    ];

    // If your primary key is not 'id', define it
    protected $primaryKey = 'id';

    public function sections()
    {
        return $this->hasMany(PolicyMasjid::class, 'policy_id');
    }
}
