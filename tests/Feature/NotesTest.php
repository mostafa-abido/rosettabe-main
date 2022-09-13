<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;

use Tests\TestCase;

class NotesTest extends TestCase
{
  use RefreshDatabase;

  public function testNotesSuccessfully()
  {
    $this->json('Get', 'api/notes')
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
