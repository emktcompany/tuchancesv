<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_participants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->integer('program_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table->timestamps();
            $table->foreign('program_id')
                ->references('id')
                ->on('programs')
                ->onDelete('set null');
            $table->unique(['email', 'program_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('program_participants');
    }
}
