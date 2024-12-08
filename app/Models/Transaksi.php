<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksi';

    protected $fillable = [
        'id_users',
        'id_instansi',
        'id_obat',
        'id_jenis_obat',
        'tanggal_order',
        'jumlah_permintaan',
        'jumlah_acc',
        'jumlah_retur',
        'total_harga',
        'status',
        'alasan_penolakan',
        'alasan_retur',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    // Relasi dengan tabel Instansi
    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'id_instansi');
    }

    // Relasi dengan tabel Obat
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }

    // Relasi dengan tabel Jenis Obat
    public function jenisObat()
    {
        return $this->belongsTo(JenisObat::class, 'id_jenis_obat');
    }
}
