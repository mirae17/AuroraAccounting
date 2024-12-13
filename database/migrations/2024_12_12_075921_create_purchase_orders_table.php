<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id('iPurchOrderPk');
            $table->unsignedBigInteger('iPurchOrderComfk');
            $table->unsignedBigInteger('iPurchOrderCustDfk');
            $table->string('iPurchOrderNo')->unique();
            $table->date('dPurchOrderdate');
            $table->decimal('yPurchOrderSubtotal', 15, 2);
            $table->decimal('iPurchOrderDiscount', 15, 2)->nullable();
            $table->decimal('iPurchOrderTax', 5, 2)->nullable();
            $table->decimal('iPurchOrderShipping', 15, 2)->nullable();
            $table->decimal('yPurchOrderTotalPayment', 15, 2);
            $table->timestamps();

            $table->foreign('iPurchOrderComfk')->references('id')->on('companies');
            $table->foreign('iPurchOrderCustDfk')->references('iCustDPk')->on('customer_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
