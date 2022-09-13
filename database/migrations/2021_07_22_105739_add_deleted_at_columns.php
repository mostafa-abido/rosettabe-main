<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      Schema::table('users', function (Blueprint $table) {
        $table->timestamp('deleted_at')->nullable(true);
      });

      Schema::table('posts', function (Blueprint $table) {
        $table->timestamp('deleted_at')->nullable(true);
      });

      Schema::table('messages', function (Blueprint $table) {
        $table->timestamp('deleted_at')->nullable(true);
      });

      Schema::table('notes', function (Blueprint $table) {
        $table->timestamp('deleted_at')->nullable(true);
      });

      Schema::table('comments', function (Blueprint $table) {
        $table->timestamp('deleted_at')->nullable(true);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('users', function (Blueprint $table) {
        $table->timestamp('deleted_at')->nullable(true);
      });

      Schema::table('posts', function (Blueprint $table) {
        $table->dropColumn('deleted_at');
      });

      Schema::table('messages', function (Blueprint $table) {
        $table->dropColumn('deleted_at');
      });

      Schema::table('notes', function (Blueprint $table) {
        $table->dropColumn('deleted_at');
      });

      Schema::table('comments', function (Blueprint $table) {
        $table->dropColumn('deleted_at');
      });
    }
}
