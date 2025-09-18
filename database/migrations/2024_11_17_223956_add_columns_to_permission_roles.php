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
        Schema::table('permissions', function (Blueprint $table) {
            $table->integer('parent_id')->default(0);
        });
        Schema::table('roles', function (Blueprint $table){
            $table->string('color')->default('#7e8299');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });
        Schema::table('roles', function (Blueprint $table){
            $table->dropColumn('color');
        });
    }
};
