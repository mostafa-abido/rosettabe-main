<?php

namespace Tests\Feature;

use Illuminate\Http\Response;

use Tests\TestCase;

class ResourcesTest extends TestCase
{
  public function testResourcesSuccessfully()
  {
    $this->json('GET', 'api/resources')
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
