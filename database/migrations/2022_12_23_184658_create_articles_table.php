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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cover_id')->nullable();
            $table->unsignedBigInteger('creator_id');
            $table->string('title');
            $table->string('slug');
            $table->string('description', 800)->nullable();
            $table->text('body')->nullable();
            $table->timestamps();
            $table->string('published_at')->nullable();
            $table->boolean('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
