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
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uploader_id')
                ->constrained('users')->onDelete('cascade');
            $table->foreignId('artist_id')
                ->constrained('artists')->onDelete('cascade');
            $table->foreignId('album_id')->nullable()
                ->constrained('albums')->onDelete('cascade');
            $table->foreignId('cover_id')->nullable()
                ->constrained('media')->onDelete('set null');

            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedInteger('duration'); // in seconds
            $table->boolean('is_mv')->default(false); // mv = music video
            $table->string('lyrics_file_url')->nullable();
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
        Schema::dropIfExists('tracks');
    }
};
