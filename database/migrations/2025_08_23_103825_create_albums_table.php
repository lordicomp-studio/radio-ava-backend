<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')
                ->constrained('users')->onDelete('cascade');
            $table->foreignId('artist_id')
                ->constrained('artists')->onDelete('cascade');
            $table->foreignId('photo_id')->nullable()
                ->constrained('media')->onDelete('cascade');

            $table->string('name');
            $table->string('slug');
            $table->boolean('is_published')->default(false);

            $table->date('release_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
