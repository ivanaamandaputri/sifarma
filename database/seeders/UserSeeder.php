<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nip' => '123',
            'password' => Hash::make('pwd123'), // Jangan lupa untuk mengganti password sesuai kebutuhan
            'role' => 'admin',
            'profile' => 'profile/admin1.jpg',
            'nama' => 'Budi Santoso',
            'jabatan' => 'Admin',
            'id_instansi' => 1, // Hanya instansi 1 (IF) yang admin puskesmas tidak 
        ]);

        // Operator pertama dengan jabatan Kepala Apotik
        User::create([
            'nip' => '111',
            'password' => Hash::make('pwd111'),
            'role' => 'operator',
            'profile' => 'profile/operator1.jpg',
            'nama' => 'Ivana Amanda',
            'jabatan' => 'Kepala Apotik',
            'id_instansi' => 2, // Pastikan id_instansi ada dalam tabel instansi
        ]);

        // Operator kedua dengan jabatan Apoteker
        User::create([
            'nip' => '222',
            'password' => Hash::make('pwd222'),
            'role' => 'operator',
            'profile' => 'profile/operator2.jpg',
            'nama' => 'Jovita Amanda',
            'jabatan' => 'Apoteker',
            'id_instansi' => 3, // Pastikan id_instansi ada dalam tabel instansi
        ]);
    }
}
