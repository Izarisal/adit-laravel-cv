<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portofolio extends Model
{
    protected $table      = 'Portofolio_1104';
    protected $primaryKey = 'id_portofolio';
    protected $fillable   = [
        'nama_lengkap', 'profesi', 'profil', 'telepon',
        'email', 'alamat', 'foto', 'tampilkan_foto',
    ];
    protected $casts = ['tampilkan_foto' => 'boolean'];

    public function pendidikan()
    {
        return $this->hasMany(Pendidikan::class, 'id_portofolio', 'id_portofolio')
            ->orderByDesc('tahun_mulai');
    }

    public function pengalaman()
    {
        return $this->hasMany(Pengalaman::class, 'id_portofolio', 'id_portofolio')
            ->orderByDesc('tahun_mulai');
    }

    public function keahlian()
    {
        return $this->hasMany(Keahlian::class, 'id_portofolio', 'id_portofolio');
    }
}
