<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;

use Tests\TestCase;

class UserTest extends TestCase
{

  public function testGetUserSuccessfully()
  {
    $this->json('Get', 'api/user')
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
