<?php

namespace Tests\Feature;

use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class LoginTest extends TestCase
{
  use RefreshDatabase;

  public function testRequiresEmailAndLogin()
  {
    $response = $this->json('POST', 'api/login');
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
      ->assertJson([
        'message' => 'The given data was invalid.',
      ]);
  }

  public function testUserLoginsSuccessfully()
  {
    $user = User::factory()->create();
    $this->json('POST', 'api/login', ['email' => $user->email, 'password' => 'password'])
      ->assertStatus(Response::HTTP_OK)
      ->assertJson([
        'user' => [
          'id' => $user->id
        ]
      ]);
  }
}
