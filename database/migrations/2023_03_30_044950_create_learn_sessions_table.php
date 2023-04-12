<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLearnSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learn_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('learn_uuid');
            $table->string('title');
            $table->string('duration');
            $table->string('schedule')->nullable();
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
        Schema::dropIfExists('learn_sessions');
    }
}
