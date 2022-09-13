<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = Message::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition(): array
  {
    return [
      'user_id' => rand(1, User::count()),
      'chat_id' => 1,
      'message' => $this->faker->text,
      'status' => Message::STATUS['PUBLISHED'],
    ];
  }
}
