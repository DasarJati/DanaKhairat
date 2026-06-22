<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TuntutanDokumen extends Model
{
    protected $table = 'tuntutan_dokumen';

    protected $fillable = [
    'tuntutan_id',
    'nama_ahli',
    'ic_ahli',
    'sijil_kematian',
    'ic_pewaris',
    'laporan_polis',
    'other_document',

];


    public $timestamps = true;

    /**
     * Dokumen milik satu tuntutan (orang meninggal)
     */
    public function tuntutan()
    {
        return $this->belongsTo(TuntutanKhairat::class, 'tuntutan_id');
    }
}
