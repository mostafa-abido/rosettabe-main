<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;

use Tests\TestCase;

class PostsTest extends TestCase
{
  public function testPostsSuccessfully()
  {
    $this->json('Get', 'api/posts')
      ->assertStatus(Response::HTTP_OK)
      ->assertJsonStructure(
        [
          "success",
          "message",
          "payload" => [],
        ]
      );

  }
}
