<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    public function testSuccessfulRegistration()
    {
        $userData = [
            "name" => "John Doe",
            "email" => "doe@example.com",
            "password" => "12345678",
            "password_confirmation" => "12345678"
        ];

        $this->json('POST', 'api/auth/user/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
            ]);
//        DB::table('users')->delete();
    }
}
