<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $table = 'dosen';
    protected $primaryKey = 'id_dosen';
    protected $fillable = [
        'id_users',
        'nip',
        'nama',
        'gelar_depan',
        'gelar_belakang',
        'tanggal_lahir',
        'jenis_kelamin',
        'id_prodi',
        'jabatan',
        'email',
        'telepon',
        'foto',
        'tanggal_bergabung',
        'status'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'id_users', 'id');
    }

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }
}
