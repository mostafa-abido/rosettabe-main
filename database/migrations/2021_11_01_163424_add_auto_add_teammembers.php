<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAutoAddTeammembers extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('chats', function (Blueprint $table) {
      $table->boolean('auto_add_teammembers')->default(false);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table(
      'chats',
      function (Blueprint $table) {
        $table->dropColumn('auto_add_teammembers');
      }
    );
  }
}
