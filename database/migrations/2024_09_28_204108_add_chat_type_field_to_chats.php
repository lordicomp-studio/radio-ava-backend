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
        Schema::table('chats', function (Blueprint $table) {
            $table->string('chat_type')->default(\App\Enums\ChatTypeEnum::Direct->value);
            $table->unsignedBigInteger('receiver_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->removeColumn('chat_type');
            $table->unsignedBigInteger('receiver_id')->nullable(false)->change();
        });
    }
};
