<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateGuestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('fname');
            $table->string('lname');
            $table->string('alias')->nullable();
            $table->text('photo1')->nullable();
            $table->text('photo2')->nullable();
            $table->enum('photo_release', ['PENDING', 'YES', 'NO', 'LIMITED'])->default('PENDING');
            $table->text('photo_notes');
            $table->string('dob')->nullable();
            $table->enum('type', ['SUITES', 'HOME CARE'])->default('SUITES');
            $table->bigInteger('home_id')->unsigned()->index();
            $table->foreign('home_id')
            ->references('id')
            ->on('homes')
            ->onDelete('cascade');
            $table->boolean('dnr')->nullable()->default(false);
            $table->enum('status', ['PENDING', 'ACTIVE', 'INACTIVE'])->default('PENDING');
            $table->text('life_story')->nullable();
            $table->text('purposefull')->nullable();
            $table->text('intellectual')->nullable();
            $table->text('social')->nullable();
            $table->text('physical')->nullable();
            $table->text('spiritual')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guests');
    }
}
