<?php

namespace Database\Seeders;

use App\Models\KategoriStandar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriStandarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KategoriStandar::create([
            'kategoriStandar_desc'  => 'KTSP 2006'
        ]);

        KategoriStandar::create([
            'kategoriStandar_desc'  => 'COBIT V5'
        ]);

        KategoriStandar::create([
            'kategoriStandar_desc'  => 'ISO 270001'
        ]);
    }
}
