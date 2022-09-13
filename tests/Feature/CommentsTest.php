<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class CommentsTest extends TestCase
{
  use RefreshDatabase;

  public function testGetPostCommentsSuccessfully()
  {
    $user = User::factory()->create();
    $post = Post::create([
      'title' => 'testing',
      'author_id' => $user->id,
      'body' => 'Testing',
      'status' => Post::PUBLISHED,
    ]);

    $this->json('POST', 'api/login', ['email' => $user->email, 'password' => 'password'])
      ->assertStatus(Response::HTTP_OK)
      ->assertJson([
        'user' => [
          'id' => $user->id
        ]
      ]);
    $this->json('POST', 'api/comments', ['body' => 'Testing Comment', 'user_id' => $user->id, 'post_id' => $post->id])
    ->assertStatus(Response::HTTP_OK);
  }
}
