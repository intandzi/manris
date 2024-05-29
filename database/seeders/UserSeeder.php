<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lembaga = User::create([
            'unit_id'       => 1,
            'name'          => 'Lembaga',
            'jabatan'       => 'Lembaga123',
            'email'         => 'lembaga@gmail.com',
            'password'      => bcrypt('password'),
            'role'          => json_encode(['lembaga', 'unit manajemen resiko', 'risk owner', 'risk officer']),
        ]);

        $lembaga->assignRole(['lembaga', 'unit manajemen resiko', 'risk owner', 'risk officer']);

        $unitmanajemenresiko = User::create([
            'unit_id'       => 2,
            'name'          => 'Unit Manajemen Resiko',
            'jabatan'       => 'unitmanajemenresiko55',
            'email'         => 'unitmanajemenresiko@gmail.com',
            'password'      => bcrypt('password'),
            'role'          => json_encode(['unit manajemen resiko', 'risk owner', 'risk officer']),
        ]);

        $unitmanajemenresiko->assignRole(['unit manajemen resiko', 'risk owner', 'risk officer']);

        $riskowner = User::create([
            'unit_id'       => 3,
            'name'          => 'Risk Owner',
            'jabatan'       => 'riskowner77',
            'email'         => 'riskowner@gmail.com',
            'password'      => bcrypt('password'),
            'role'          => json_encode(['risk owner', 'risk officer']),
        ]);

        $riskowner->assignRole(['risk owner', 'risk officer']);

        $riskofficer = User::create([
            'unit_id'       => 2,
            'name'          => 'Risk Officer',
            'jabatan'       => 'riskofficer90',
            'email'         => 'riskofficer@gmail.com',
            'password'      => bcrypt('password'),
            'role'          => json_encode(['risk officer']),
        ]);

        $riskofficer->assignRole('risk officer');
    }
}
