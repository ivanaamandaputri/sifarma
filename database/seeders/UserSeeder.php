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
            'nip' => 'ADMIN001',
            'password' => Hash::make('password123'), // Jangan lupa untuk mengganti password sesuai kebutuhan
            'role' => 'admin',
            'profile' => 'profile/admin1.jpg',
            'nama' => 'Admin 1',
            'jabatan' => 'Admin',
            'id_instansi' => 1, // Pastikan id_instansi ada dalam tabel instansi
        ]);

        // Operator pertama dengan jabatan Kepala Apotik
        User::create([
            'nip' => 'OPERATOR001',
            'password' => Hash::make('password123'),
            'role' => 'operator',
            'profile' => 'profile/operator1.jpg',
            'nama' => 'Kepala Apotik',
            'jabatan' => 'Kepala Apotik',
            'id_instansi' => 1, // Pastikan id_instansi ada dalam tabel instansi
        ]);

        // Operator kedua dengan jabatan Apoteker
        User::create([
            'nip' => 'OPERATOR002',
            'password' => Hash::make('password123'),
            'role' => 'operator',
            'profile' => 'profile/operator2.jpg',
            'nama' => 'Apoteker',
            'jabatan' => 'Apoteker',
            'id_instansi' => 1, // Pastikan id_instansi ada dalam tabel instansi
        ]);
    }
}
