<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratorium extends Model
{
    protected $table = 'laboratorium';
    protected $primaryKey = 'id_lab';
    protected $fillable = [
        'id_admin',
        'nama',
        'kode',
        'lokasi',
        'kapasitas',
        'deskripsi',
        'status',
        'foto'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin', 'id');
    }

    public function alat()
    {
        return $this->hasMany(Alat::class, 'id_lab', 'id_lab');
    }
}
