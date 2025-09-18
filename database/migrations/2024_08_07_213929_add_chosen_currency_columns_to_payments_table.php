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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('chosen_currency')->nullable();
            $table->decimal('chosen_currency_price', 65, 0)->nullable();
//            $table->decimal('chosen_currency_price', 78, 0)->nullable(); // should be 78 but mysql doesn't support more than 65.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('chosen_currency');
            $table->dropColumn('chosen_currency_price');
        });
    }
};
