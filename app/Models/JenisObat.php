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

    public function obat()
    {
        return $this->hasMany(Obat::class, 'jenis_obat');
    }

    // Relasi dengan Transaksi
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    // Mutator untuk nama_jenis, mengubah huruf pertama menjadi kapital
    public function setNamaJenisAttribute($value)
    {
        $this->attributes['nama_jenis'] = ucwords(strtolower($value)); // Mengubah huruf pertama menjadi kapital
    }
}
