<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\User;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ChatsTest extends TestCase
{
  use RefreshDatabase;

  public function testGetUserChatsSuccessfully()
  {
    $user = User::factory()->create();
    $chat = Chat::factory()->create();
    $chat->members()->save($user);

    $this->json('POST', 'api/login', ['email' => $user->email, 'password' => 'password'])
      ->assertStatus(Response::HTTP_OK)
      ->assertJson([
        'user' => [
          'id' => $user->id
        ]
      ]);

    $this->json('GET', 'api/chats')
      ->assertStatus(Response::HTTP_OK);
  }

  public function testGetChatSuccessfully()
  {
    $user = User::factory()->create();
    $chat = Chat::factory()->create();
    $chat->members()->save($user);

    $this->json('POST', 'api/login', ['email' => $user->email, 'password' => 'password'])
      ->assertStatus(Response::HTTP_OK)
      ->assertJson([
        'user' => [
          'id' => $user->id
        ]
      ]);
     $this->json('GET', 'api/chats/'.$chat->id)
      ->assertStatus(Response::HTTP_OK)
      ->assertJson([
        'data' => [
          'id' => $chat->id
        ]
      ]);
  }
}
