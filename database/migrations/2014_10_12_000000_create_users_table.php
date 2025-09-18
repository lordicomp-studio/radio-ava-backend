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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('first_name')->nullable(); // added by us
            $table->string('last_name')->nullable(); // added by us
            $table->string('email')->unique();
            $table->text('bio')->nullable();
            $table->foreignId('profile_photo_id')->nullable();
            $table->string('level')->default(\App\Enums\UserLevelEnum::Client->value);

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

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
        Schema::dropIfExists('users');
    }
};
