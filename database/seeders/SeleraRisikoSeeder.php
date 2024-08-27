<?php

namespace Database\Seeders;

use App\Models\SeleraRisiko;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeleraRisikoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SeleraRisiko::create([
            'derajatRisiko_id'              => 1,
            'seleraRisiko_desc'             => '-',
            'seleraRisiko_tindakLanjut'     => '-',
        ]);
        SeleraRisiko::create([
            'derajatRisiko_id'              => 2,
            'seleraRisiko_desc'             => '-',
            'seleraRisiko_tindakLanjut'     => '-',
        ]);
        SeleraRisiko::create([
            'derajatRisiko_id'              => 3,
            'seleraRisiko_desc'             => '-',
            'seleraRisiko_tindakLanjut'     => '-',
        ]);
    }
}
