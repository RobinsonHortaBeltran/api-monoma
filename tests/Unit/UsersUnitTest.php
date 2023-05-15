<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;

class UsersUnitTest extends TestCase
{
    use RefreshDatabase, InteractsWithDatabase;

    public function testCreateUser()
    {
        // Preparar
         $userData = [
            'username' => 'John Doe',
            'last_login' => now(),
            'password' => bcrypt('password'),
            'role'       => 'manager',
            'active'     => true,
        ];

        // Ejecutar
        $user = User::create($userData);

        // Verificar
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->username);
       // $this->assertEquals('john@example.com', $user->email);
    }

    public function testUpdateUser()
    {
        // Preparar
        $user = User::factory()->create();

        // Ejecutar
        $user->username = 'Updated Name';
        $user->save();

        // Verificar
        $this->assertEquals('Updated Name', $user->username);
    }

    public function testDeleteUser()
    {
        // Preparar
        $user = User::factory()->create();

        // Ejecutar
        $user->delete();

        // Verificar
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
