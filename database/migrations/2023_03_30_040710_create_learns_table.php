<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLearnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learns', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('instructor_uuid');
            $table->string('dp_category');
            $table->string('title');
            $table->mediumText('overview');
            $table->integer('slot')->nullable();
            $table->tinyInteger('type');
            $table->string('level');
            $table->string('language');
            $table->string('certification');
            $table->boolean('approved');
            $table->boolean('status');
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
        Schema::dropIfExists('learns');
    }
}
