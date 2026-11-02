<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendidikan extends Model
{
    protected $table      = 'Pendidikan_1104';
    protected $primaryKey = 'id_pendidikan';
    protected $fillable   = ['id_portofolio', 'tahun_mulai', 'tahun_selesai', 'institusi', 'jurusan'];
}
