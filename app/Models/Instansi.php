<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak sesuai dengan penamaan default
    protected $table = 'instansi';

    // Tentukan kolom yang bisa diisi (fillable) untuk mass assignment
    protected $fillable = [
        'nama',
    ];

    // Tentukan kolom yang tidak bisa diisi (guarded) jika diperlukan
    // protected $guarded = [];

    // Relasi satu ke banyak dengan users
    public function users()
    {
        return $this->hasMany(User::class, 'id_instansi');
    }
}
