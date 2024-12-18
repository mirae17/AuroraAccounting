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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id('iInvcItemPk'); //quotation id
            $table->unsignedBigInteger('iInvcItemInvcfk');//invoice id foreign key referencing quotations.iInvcPk
            $table->string('cInvcItemProductCode');//item_code
            $table->string('cInvcItemDescription');//item_name
            $table->decimal('yInvcItemPriceUnit', 15, 2);//price_per_unit
            $table->integer('iInvcItemQuantity');//quantity
            $table->decimal('yInvcItemTotal', 15, 2);//amount
            $table->timestamps();

            $table->foreign('iInvcItemInvcfk')->references('iInvcPk')->on('invoices');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
