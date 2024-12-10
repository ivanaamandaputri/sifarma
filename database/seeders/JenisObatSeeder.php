<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisObatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jenis_obat')->insert([
            ['nama' => 'Sirup'],
            ['nama' => 'Tablet'],
            ['nama' => 'Kapsul'],
            ['nama' => 'Salep'],
            ['nama' => 'Injeksi'],
        ]);
    }
}
