<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisObat extends Model
{
    use HasFactory;
    protected $table = 'jenis_obat';
    protected $fillable = [
        'nama', // Nama jenis obat
    ];
}
