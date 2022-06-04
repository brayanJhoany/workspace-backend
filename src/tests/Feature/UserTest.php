<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Constraints\HasInDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testShouldListUsersPagined()
    {
        User::factory(30)->create();
        $response = $this->get('/protected/users/10/1/');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'totalItems',
            'totalPages',
            'hasNext',
            'hasPrevious',
            'users' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'role' => [
                        'id',
                        'name',
                        'code',
                    ],
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
    }
    public function testShouldShowUser()
    {
        $user = User::factory()->create();
        $response = $this->get('/protected/user/' . $user->id);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ]);
        $response->assertJsonStructure([
            'id',
            'name',
            'email',
            'role' => [
                'id',
                'name',
                'code',
            ],
            'created_at',
            'updated_at'
        ]);
    }
    public function testShouldStoreUser()
    {
        //create role
        //register defual role
        Role::factory()->create([
            'name' => 'regular',
            'code' => 'REGULAR',
        ]);
        $params = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password
        ];
        $response = $this->post('/protected/user', $params);
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'name' => $params['name'],
            'email' => $params['email'],
        ]);
        $user = User::where('email', $params['email'])->first();
        $this->assertTrue(Hash::check($params['password'], $user->password));
        $response->assertJsonStructure([
            'message',
            'user' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at'
            ]
        ]);
    }
    public function testShouldUpdateUser()
    {
        $user = User::factory()->create();
        $params = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password
        ];
        $response = $this->put('/protected/user/' . $user->id, $params);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'user' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at'
            ]
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $params['name'],
            'email' => $params['email'],
        ]);
        $user = User::where('email', $params['email'])->first();
        $this->assertTrue(Hash::check($params['password'], $user->password));
    }
    public function testShouldDeleteUser()
    {
        $user  = User::factory()->create();
        $response = $this->delete('/protected/user/' . $user->id);
        $response->assertStatus(200);
        $response->assertJson(['message' => 'User deleted successfully']);
        // check change delete status in database
        $this->assertSoftDeleted($user);
    }
}
