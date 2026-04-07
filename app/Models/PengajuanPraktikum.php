<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanPraktikum extends Model
{
    protected $table = 'pengajuan_praktikum';
    protected $primaryKey = 'id_pengajuan';
    protected $fillable = [
        'id_users',
        'id_kategori',
        'id_lab',
        'id_makul',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'jobsheet',
        'status',
        'catatan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users', 'id');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function lab()
    {
        return $this->belongsTo(Laboratorium::class, 'id_lab', 'id_lab');
    }

    public function makul()
    {
        return $this->belongsTo(MataKuliah::class, 'id_makul', 'id_makul');
    }
}
