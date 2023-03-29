<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feeds', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('caption');
            $table->string('user_uuid');
            $table->smallInteger('user_type');
            $table->string('feed_p_category_uuid');
            $table->json('product_uuid')->nullable();
            $table->string('course_uuid')->nullable();
            $table->string('workshop_uuid')->nullable();
            $table->smallInteger('type');
            $table->json('src')->nullable();
            $table->integer('views')->default(0);
            $table->integer('share')->default(0);
            $table->string('thumbnail')->nullable();
            $table->tinyInteger('is_active')->default(0);
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('feeds');
    }
};
