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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('parent_id')->nullable();
            $table->foreignId('photo_id')->nullable();

            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });
        Schema::create('catables', function (Blueprint $table) {
            $table->bigInteger('category_id');
            $table->bigInteger('catable_id');
            $table->string('catable_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('catables');
    }
};
