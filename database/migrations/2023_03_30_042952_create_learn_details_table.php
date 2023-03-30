<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLearnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learn_details', function (Blueprint $table) {
            $table->id();
            $table->string('learn_uuid');
            $table->decimal('price', 8, 2);
            $table->decimal('discount', 8, 2);
            $table->tinyInteger('discount_type');
            $table->string('discount_duration');
            $table->json('src');
            $table->tinyInteger('type');
            $table->string('thumbnail')->nullable();
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
        Schema::dropIfExists('learn_details');
    }
}
