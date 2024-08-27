<?php

namespace Database\Seeders;

use App\Models\Stakeholders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StakeholdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Stakeholders::create([
            'stakeholder_jabatan'   => 'Ketua Prodi Sistekin',
            'stakeholder_singkatan' => 'KPS',
        ]);
        Stakeholders::create([
            'stakeholder_jabatan'   => 'Ketua Prodi Informati',
            'stakeholder_singkatan' => 'KPI',
        ]);
        Stakeholders::create([
            'stakeholder_jabatan'   => 'Ketua Internal Audit',
            'stakeholder_singkatan' => 'KIA',
        ]);
        Stakeholders::create([
            'stakeholder_jabatan'   => 'Sekretaris Internal Audit',
            'stakeholder_singkatan' => 'SIA',
        ]);
        Stakeholders::create([
            'stakeholder_jabatan'   => 'Ketua Biro Akademik',
            'stakeholder_singkatan' => 'KBA',
        ]);
        Stakeholders::create([
            'stakeholder_jabatan'   => 'Ketua LPPM',
            'stakeholder_singkatan' => 'KLP',
        ]);
        Stakeholders::create([
            'stakeholder_jabatan'   => 'Ketua Badan Penjamin Mutu',
            'stakeholder_singkatan' => 'KBPM',
        ]);
        Stakeholders::create([
            'stakeholder_jabatan'   => 'Ketua Sumber Daya Manusia',
            'stakeholder_singkatan' => 'KSDM',
        ]);
    }
}
