<?php

namespace Tests\Feature;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testShouldListRolesPagined()
    {
        Role::factory(30)->create();
        $response = $this->get('/protected/roles/1/1/');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'totalItems',
            'totalPages',
            'hasNext',
            'hasPrevious',
            'roles' => [
                '*' => [
                    'id',
                    'name',
                    'code',
                    'description',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
    }
    public function testShouldShowRole()
    {
        $role = Role::factory()->create();
        $response = $this->get('/protected/role/' . $role->id);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'code',
            'description',
            'created_at',
            'updated_at'
        ]);
    }
    public function testShouldStoreRole()
    {
        $response = $this->post('/protected/role', [
            'name' => 'Test',
            'code' => 'TEST',
            'description' => 'Test'
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'name',
            'code',
            'description',
            'created_at',
            'updated_at'
        ]);
    }
    public function testShouldUpdateRole()
    {
        $role = Role::factory()->create();
        $response = $this->put('/protected/role/' . $role->id, [
            'name' => 'Test',
            'code' => 'TEST',
            'description' => 'Test'
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'code',
            'description',
            'created_at',
            'updated_at'
        ]);
    }
    public function testShouldDeleteRole()
    {
        $role = Role::factory()->create();
        $response = $this->delete('/protected/role/' . $role->id);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message'
        ]);
    }
}
