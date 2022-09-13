<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Post::factory()->count(100)->create()->each(function($post) {
        $faker = \Faker\Factory::create();
        DB::table('attachments')->insert([
          'attachable_id' => $post->id,
          'attachable_type' => "App\Models\Post",
          'filename' => $faker->text(50),
          'path' => 'http://127.0.0.1/storage/attachments/'.$faker->image('storage/app/public/attachments', 400, 300, null, false),
          'user_id' => 1,
          'created_at' => now(),
          'updated_at' => now(),
        ]);
       });
    }
}
