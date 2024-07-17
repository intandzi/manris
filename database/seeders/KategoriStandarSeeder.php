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
            'kategoriStandar_desc'  => 'abc'
        ]);

        KategoriStandar::create([
            'kategoriStandar_desc'  => 'cde'
        ]);

        KategoriStandar::create([
            'kategoriStandar_desc'  => 'fgh'
        ]);
    }
}
