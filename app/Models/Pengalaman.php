<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengalaman extends Model
{
    protected $table      = 'Pengalaman_1104';
    protected $primaryKey = 'id_pengalaman';
    protected $fillable   = ['id_portofolio', 'tahun_mulai', 'tahun_selesai', 'jabatan', 'deskripsi'];
}
