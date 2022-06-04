<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::factory()->create([
            'name'          => 'Administrador',
            'description'   => 'Se le permite acceder a todo el sistema',
            'code'          => 'ADMIN'
        ]);
        Role::factory()->create([
            'name'          => 'Regular',
            'description'   => 'solo tiene permitido ver su informacion',
            'code'          => 'REGULAR'
        ]);
    }
}
