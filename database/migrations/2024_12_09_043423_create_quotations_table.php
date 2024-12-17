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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id('iQuoPk');
            $table->unsignedBigInteger('iQuoComfk');//company user's details
            $table->unsignedBigInteger('iQuoCustDfk');//customer details
            $table->string('iQuoNo')->unique();//quoatation no 
            $table->date('dQuodate');//quotation date
            $table->decimal('yQuoSubtotal', 15, 2);//subtotal
            $table->decimal('iQuoDiscount', 15, 2)->nullable();//discount
            $table->decimal('iQuoTax', 5, 2)->nullable();//tax
            $table->decimal('iQuoShipping', 15, 2)->nullable();//shipping
            $table->decimal('yQuoTotalPayment', 15, 2);//total payment
            $table->timestamps();

            $table->foreign('iQuoComfk')->references('id')->on('companies');
            $table->foreign('iQuoCustDfk')->references('iCustDPk')->on('customer_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
