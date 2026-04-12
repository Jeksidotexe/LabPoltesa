<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    protected $table = 'alat';
    protected $primaryKey = 'id_alat';
    protected $fillable = [
        'id_lab',
        'nama_alat',
        'spesifikasi_alat',
        'instruksi_kerja',
        'tahun_pengadaan',
        'jumlah',
        'jumlah_rusak_ringan',
        'jumlah_rusak_berat',
        'kondisi',
        'foto'
    ];

    public function lab()
    {
        return $this->belongsTo(Laboratorium::class, 'id_lab', 'id_lab');
    }
}
