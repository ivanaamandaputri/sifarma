<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokMasuk extends Model
{
    use HasFactory;
    protected $table = 'stok_masuk';
    protected $fillable = [
        'id_obat',
        'jumlah',
        'sumber',
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }
}
