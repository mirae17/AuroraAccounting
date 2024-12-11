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
            $table->unsignedBigInteger('iQuoComfk');
            $table->unsignedBigInteger('iQuoCustDfk');
            $table->string('iQuoNo')->unique();
            $table->date('dQuodate');
            $table->decimal('yQuoSubtotal', 15, 2);
            $table->decimal('iQuoDiscount', 15, 2)->nullable();
            $table->decimal('iQuoTax', 5, 2)->nullable();
            $table->decimal('iQuoShipping', 15, 2)->nullable();
            $table->decimal('yQuoTotalPayment', 15, 2);
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
