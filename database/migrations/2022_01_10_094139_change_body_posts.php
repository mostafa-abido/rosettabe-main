<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBodyPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table(
        'posts',
        function (Blueprint $table) {
          $table->text('body')->nullable()->change();
        }
      );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table(
        'posts',
        function (Blueprint $table) {
          $table->text('body')->nullable(false)->change();
        }
      );
    }
}
