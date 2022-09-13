<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateHomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['English Rose Suite', 'Clients Home', 'Other'])
            ->default('Other');
            $table->string('picture')->nullable();
            $table->text('address');
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->bigInteger('manager_id')->unsigned()->index()->nullable();
            $table->foreign('manager_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
            $table->bigInteger('nurse_id')->unsigned()->index()->nullable();
            $table->foreign('nurse_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
            $table->bigInteger('team_lead_id')->unsigned()->index()->nullable();
            $table->foreign('team_lead_id')
              ->references('id')
              ->on('users')
              ->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->enum('status', ['PENDING', 'ACTIVE', 'INACTIVE'])->default('PENDING')->nullable();
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
        Schema::dropIfExists('homes');
    }
}
