<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nip',
        'password',
        'role',
        'profile',
        'nama',
        'jabatan',
        'id_instansi',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];
    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'id_instansi');
    }
    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = ucwords(strtolower($value)); // Ubah huruf pertama menjadi kapital
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = ($value);
    }
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_users');
    }
}
