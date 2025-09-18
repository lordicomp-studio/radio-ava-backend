<?php

use App\Enums\PaymentStatusEnum;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payer_id');
            $table->string('track_id')->nullable();
            $table->string('code')->unique();
            $table->string('price')->nullable(); // TODO why is it a string!? change it to unsignedBigInteger.
            $table->string('status')->default(PaymentStatusEnum::Pending->value);
            $table->string('gateway');
            $table->timestamps();
        });

        Schema::create('paymentables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('paymentable_id');
            $table->string('paymentable_type');
            // parent_id is used to determine when for example a feature is a child of a product.
            $table->unsignedBigInteger('parent_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('paymentables');
    }
};
