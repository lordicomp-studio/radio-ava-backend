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
        Schema::create('playlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')
                ->constrained('users')->onDelete('cascade');
            $table->foreignId('photo_id')->nullable()
                ->constrained('media')->onDelete('set null');

            $table->string('name');
            $table->string('slug');
            $table->boolean('is_published')->default(false);

            $table->timestamps();
        });

        Schema::create('playlist_tracks', function (Blueprint $table) {
            $table->foreignId('playlist_id')
                ->constrained('playlists')->onDelete('cascade');
            $table->foreignId('track_id')
                ->constrained('tracks')->onDelete('cascade');

            $table->primary(['playlist_id', 'track_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlists');
        Schema::dropIfExists('playlist_tracks');
    }
};
