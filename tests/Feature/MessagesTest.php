<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class MessagesTest extends TestCase
{
  use RefreshDatabase;

  public function testSendMessageSuccessfully()
  {
    $user = User::factory()->create();
    $chat = Chat::factory()->create();

    $this->json('POST', 'api/login', ['email' => $user->email, 'password' => 'password'])
      ->assertStatus(Response::HTTP_OK)
      ->assertJson([
        'user' => [
          'id' => $user->id
        ]
      ]);

   $this->json('POST', 'api/messages', ['message' => 'Testing', 'user_id' => $user->id, 'chat_id' => $chat->id, 'status' => 'PUBLISHED'])
     ->assertStatus(Response::HTTP_CREATED);
  }
}
