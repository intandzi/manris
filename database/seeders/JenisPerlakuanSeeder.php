<?php

namespace Database\Seeders;

use App\Models\JenisPerlakuan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisPerlakuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JenisPerlakuan::create([
            'jenisPerlakuan_desc'   => 'risk mitigation',
        ]);
        JenisPerlakuan::create([
            'jenisPerlakuan_desc'   => 'retain',
        ]);
        JenisPerlakuan::create([
            'jenisPerlakuan_desc'   => 'sharing',
        ]);
        JenisPerlakuan::create([
            'jenisPerlakuan_desc'   => 'avoidance',
        ]);
        JenisPerlakuan::create([
            'jenisPerlakuan_desc'   => 'exploit',
        ]);
    }
}
