<?php

namespace Database\Seeders;

use App\Models\DerajatRisiko;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DerajatRisikoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DerajatRisiko::create([
            'derajatRisiko_desc'                => 'rendah',
            'derajatRisiko_nilaiTingkatMin'     => 1,
            'derajatRisiko_nilaiTingkatMax'     => 250,
        ]);
        DerajatRisiko::create([
            'derajatRisiko_desc'                => 'sedang',
            'derajatRisiko_nilaiTingkatMin'     => 251,
            'derajatRisiko_nilaiTingkatMax'     => 500,
        ]);
        DerajatRisiko::create([
            'derajatRisiko_desc'                => 'tinggi',
            'derajatRisiko_nilaiTingkatMin'     => 501,
            'derajatRisiko_nilaiTingkatMax'     => 1000,
        ]);
    }
}
