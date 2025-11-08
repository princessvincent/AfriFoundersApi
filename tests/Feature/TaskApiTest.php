<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;


    public function test_user_can_register()
    {
        $response = $this->post('/api/register', [
            'name' => 'Prisca Eze',
            'email' => 'prisca@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user' => ['id', 'name', 'email', 'created_at', 'updated_at']
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'prisca@example.com'
        ]);
    }

    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'prisca@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/api/user-login',[
            'email' => 'prisca@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user',
                'access_token',
                'token_type',
            ]);
    }

    public function test_authenticated_user_can_create_task()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->post('/api/task', [
            'title' => 'Test Task',
            'description' => 'This is a test task',
            'status' => 'pending',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Task created successfully',
                'task' => [
                    'title' => 'Test Task',
                    'description' => 'This is a test task',
                    'status' => 'pending'
                ]
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'user_id' => $user->id
        ]);
    }

}
