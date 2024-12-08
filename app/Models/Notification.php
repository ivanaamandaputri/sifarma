<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    use HasFactory;

    // Tentukan nama tabel
    protected $table = 'notification';

    // Tentukan kolom yang bisa diisi (fillable)
    protected $fillable = [
        'id_users',
        'judul',
        'isi',
        'is_read',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }
}
