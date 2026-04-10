<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    protected $table = 'program_studi';
    protected $primaryKey = 'id_prodi';
    protected $fillable = [
        'kode',
        'nama_prodi',
        'akreditasi',
        'tanggal_berdiri'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'id_prodi', 'id_prodi');
    }

    public function makul()
    {
        return $this->hasMany(MataKuliah::class, 'id_prodi', 'id_prodi');
    }
}
