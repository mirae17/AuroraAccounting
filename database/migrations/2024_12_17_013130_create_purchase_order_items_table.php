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
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id('iPurchOrderItemPk'); //quotation id
            $table->unsignedBigInteger('iPurchOrderItemPurchOrderfk');//invoice id foreign key referencing quotations.iPurchOrderPk
            $table->string('cPurchOrderItemProductCode');//item_code
            $table->string('cPurchOrderItemDescription');//item_name
            $table->decimal('yPurchOrderItemPriceUnit', 15, 2);//price_per_unit
            $table->integer('iPurchOrderItemQuantity');//quantity
            $table->decimal('yPurchOrderItemTotal', 15, 2);//amount
            $table->timestamps();

            $table->foreign('iPurchOrderItemPurchOrderfk')->references('iPurchOrderPk')->on('purchase_orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
