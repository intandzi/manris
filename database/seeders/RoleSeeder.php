<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name'          => 'lembaga',
            'guard_name'    => 'web'
        ]);
        Role::create([
            'name'          => 'unit manajemen risiko',
            'guard_name'    => 'web'
        ]);
        Role::create([
            'name'          => 'risk owner',
            'guard_name'    => 'web'
        ]);
        Role::create([
            'name'          => 'risk officer',
            'guard_name'    => 'web'
        ]);
    }
}
