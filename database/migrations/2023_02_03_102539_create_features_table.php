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
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id');
            $table->string('title');
            $table->string('description', 1024)->nullable();
            $table->timestamps();
        });
        Schema::create('feature_product', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('feature_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('features');
        Schema::dropIfExists('feature_product');
    }
};
