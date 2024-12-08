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
    public function jenisObat()
    {
        return $this->belongsTo(JenisObat::class, 'jenis_obat');
    }
}
