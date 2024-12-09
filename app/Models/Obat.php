<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;
    protected $table = 'obat';
    protected $fillable = [
        'jenis_obat',
        'nama',
        'dosis',
        'stok',
        'harga',
        'exp',
        'keterangan',
        'foto',
    ];
    public function setNamaObatAttribute($value)
    {
        $this->attributes['nama_obat'] = ucwords(strtolower($value)); // Ubah huruf pertama menjadi kapital
    }

    public function setDosisAttribute($value)
    {
        $this->attributes['dosis'] = ucwords(strtolower($value)); // Ubah huruf pertama menjadi kapital
    }

    public function jenisObat()
    {
        return $this->belongsTo(JenisObat::class, 'jenis_obat');
    }
    public function stokMasuk()
    {
        return $this->hasMany(StokMasuk::class);
    }
}
