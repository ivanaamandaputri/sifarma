<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstansiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('instansi')->insert([
            ['nama' => 'Instalasi Farmasi'],
            ['nama' => 'Puskesmas Kaligangsa'],
            ['nama' => 'Puskesmas Margadana'],
            ['nama' => 'Puskesmas Tegal Barat'],
            ['nama' => 'Puskesmas Debong Lor'],
            ['nama' => 'Puskesmas Tegal Timur'],
            ['nama' => 'Puskesmas Slerok'],
            ['nama' => 'Puskesmas Tegal Selatan'],
            ['nama' => 'Puskesmas Bandung'],
        ]);
    }
}
