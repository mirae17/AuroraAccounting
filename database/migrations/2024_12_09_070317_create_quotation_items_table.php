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
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id('iQuoItemPk'); //quotation id
            $table->unsignedBigInteger('iQuoItemQuofk');//quotation id foreign key referencing quotations.iQuoPk
            $table->string('cQuoItemProductCode');//item_code
            $table->string('cQuoItemDescription');//item_name
            $table->decimal('yQuoItemPriceUnit', 15, 2);//price_per_unit
            $table->integer('iQuoItemQuantity');//quantity
            $table->decimal('yQuoItemTotal', 15, 2);//amount
            $table->timestamps();

            $table->foreign('iQuoItemQuofk')->references('iQuoPk')->on('quotations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
    }
};
