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
        Schema::create('receipt_items', function (Blueprint $table) {
            $table->id('iRecptItemPk'); //quotation id
            $table->unsignedBigInteger('iRecptItemRecptfk');//invoice id foreign key referencing quotations.iRecptPk
            $table->string('cRecptItemProductCode');//item_code
            $table->string('cRecptItemDescription');//item_name
            $table->decimal('yRecptItemPriceUnit', 15, 2);//price_per_unit
            $table->integer('iRecptItemQuantity');//quantity
            $table->decimal('yRecptItemTotal', 15, 2);//amount
            $table->timestamps();

            $table->foreign('iRecptItemRecptfk')->references('iRecptPk')->on('receipts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_items');
    }
};
