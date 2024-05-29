<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::create([
            'unit_name'     => 'Prodi Teknik Informatika',
            'created_by'    => 1,
        ]);

        Unit::create([
            'unit_name'     => 'Biro Akademik',
            'created_by'    => 1,
        ]);

        Unit::create([
            'unit_name'     => 'Badan Penjamin Mutu',
            'created_by'    => 1,
        ]);
    }
}
