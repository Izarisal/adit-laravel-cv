<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keahlian extends Model
{
    protected $table      = 'Keahlian_1104';
    protected $primaryKey = 'id_keahlian';
    protected $fillable   = ['id_portofolio', 'nama_keahlian'];
}
