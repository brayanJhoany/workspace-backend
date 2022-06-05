<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('code', 'ADMIN')->first(['id']);
        User::factory()->create([
            "name"      => "Brayan Escobar",
            "email"     => "brayan.escobar@live.com",
            "role_id" => $role->id,
            "password"  => bcrypt("123456789"),
            'email_verified_at' => now(),
        ]);

        User::factory(100)->create();
    }
}
