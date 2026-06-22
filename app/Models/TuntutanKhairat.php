<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TuntutanKhairat extends Model
{
    use HasFactory;

    protected $table = 'tuntutan_khairat';

    protected $fillable = [
        'masjid_id',
        'user_id',
        'ahli_id',
        'tanggungan_id',
        'type',
        'date_death',
        'status',
        'approve_by',
        'approved_at',
        'note',
        'death_certificate',
        'police_report',
        'other_report',
        'amount',
    ];

    protected $casts = [
        'date_death' => 'date',
        'approved_at' => 'datetime',
    ];

    // Relationship with User (pemohon)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship with Tanggungan - FIXED: use 'tanggungan_id'
    public function tanggungan()
    {
        return $this->belongsTo(Tanggungan::class, 'tanggungan_id');
    }

    // Relationship with AhliKariah
    public function ahli()
    {
        return $this->belongsTo(AhliKariah::class, 'ahli_id');
    }

    // Alias for ahliKariah to maintain consistency
    // public function ahliKariah()
    // {
    //     return $this->belongsTo(AhliKariah::class, 'ahli_id');
    // }

    public function masjid()
    {
        return $this->belongsTo(masjid::class, 'masjid_id');
    }

    // Relationship with AJK who approved - FIXED: use 'approve_by'
    public function diluluskanOleh()
    {
        return $this->belongsTo(User::class, 'approve_by');
    }

    // Relationship with Payment - FIXED: use correct model name
    public function pembayaran()
    {
        return $this->hasOne(PembayaranKhairat::class, 'tuntutan_id');
    }

    // Accessor for nama_ahli
    public function getNamaAhliAttribute()
    {
        if ($this->type === 'ahli' && $this->ahli) {
            return $this->ahli->nama;
        } elseif ($this->type === 'tanggungan' && $this->tanggungan) {
            return $this->tanggungan->nama;
        }

        return $this->attributes['nama_ahli'] ?? 'N/A';
    }

    // Accessor for ic_ahli
    public function getIcAhliAttribute()
    {
        if ($this->type === 'ahli' && $this->ahli) {
            return $this->ahli->ic;
        } elseif ($this->type === 'tanggungan' && $this->tanggungan) {
            return $this->tanggungan->ic_number;
        }

        return $this->attributes['ic_ahli'] ?? 'N/A';
    }

    // Accessor for tarikh_meninggal (alias for date_death)
    public function getTarikhMeninggalAttribute()
    {
        return $this->date_death;
    }

    public function items()
    {
        return $this->hasMany(KhairatItems::class, 'tuntutan_id');
    }
}
