<?php

namespace Tests\Feature;

use App\Infrastructure\Persistence\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function itShouldSuccessfullyRegisterToApi(): void
    {
        $response = $this->json('POST', '/api/auth/register', [
            'name' => 'John Doe',
            'email' => 'john_doe@test.php',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'access_token',
                'token_type',
            ])
            ->json();

        $this->assertNotEmpty($response['access_token']);
        $this->assertEquals('Bearer', $response['token_type']);
    }

    #[Test]
    public function itShouldSuccessfullyLoginToApi(): void
    {
        $password = 'password';
        $email = 'john.doe.test@test.php';

        UserFactory::create([
            'name' => 'John Doe',
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        $response = $this->json('POST', '/api/auth/login', [
            'name' => 'John Doe',
            'email' => $email,
            'password' => $password,
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
            ])
            ->json();

        $this->assertNotEmpty($response['access_token']);
        $this->assertEquals('Bearer', $response['token_type']);
    }

    #[Test]
    public function itShouldFailToRegisterWithMissingFields(): void
    {
        $this->json('POST', '/api/auth/register', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);

        $this->json('POST', '/api/auth/register', [
            'email' => 'john_doe@test.php',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);

        $this->json('POST', '/api/auth/register', [
            'name' => 'John Doe',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);

        $this->json('POST', '/api/auth/register', [
            'name' => 'John Doe',
            'email' => 'john_doe@test.php',
            'password_confirmation' => 'password',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    #[Test]
    public function itShouldFailToRegisterWithInvalidEmail(): void
    {
        $this->json('POST', '/api/auth/register', [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function itShouldFailToRegisterWithShortPassword(): void
    {
        $this->json('POST', '/api/auth/register', [
            'name' => 'John Doe',
            'email' => 'john_doe@test.php',
            'password' => '123',
            'password_confirmation' => '123',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    #[Test]
    public function itShouldFailToRegisterWithPasswordMismatch(): void
    {
        $this->json('POST', '/api/auth/register', [
            'name' => 'John Doe',
            'email' => 'john_doe@test.php',
            'password' => 'password',
            'password_confirmation' => 'different-password',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    #[Test]
    public function itShouldFailToRegisterWithExistingEmail(): void
    {
        UserFactory::create([
            'email' => 'john_doe@test.php',
        ]);

        $this->json('POST', '/api/auth/register', [
            'name' => 'John Doe',
            'email' => 'john_doe@test.php',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
