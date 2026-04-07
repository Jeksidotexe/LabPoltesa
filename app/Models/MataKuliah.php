<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliah';
    protected $primaryKey = 'id_makul';
    protected $fillable = [
        'kode',
        'nama',
        'sks',
        'id_prodi'
    ];

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function pengajuan()
    {
        return $this->hasMany(PengajuanPraktikum::class, 'id_makul', 'id_makul');
    }
}
