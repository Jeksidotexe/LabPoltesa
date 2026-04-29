<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeritaAcara extends Model
{
    protected $table = 'berita_acara';
    protected $primaryKey = 'id_berita_acara';
    protected $guarded = [];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanPraktikum::class, 'id_pengajuan', 'id_pengajuan');
    }
}
