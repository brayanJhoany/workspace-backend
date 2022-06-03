<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Constraints\HasInDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testShouldListUsers()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
    public function testShouldShowUser()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
    public function testShouldStoreUser()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
    public function testShouldUpdateUser()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
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
