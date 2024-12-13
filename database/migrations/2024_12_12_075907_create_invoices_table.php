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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id('iInvcPk');
            $table->unsignedBigInteger('iInvcComfk');
            $table->unsignedBigInteger('iInvcCustDfk');
            $table->string('iInvcNo')->unique();
            $table->date('dInvcdate');
            $table->decimal('yInvcSubtotal', 15, 2);
            $table->decimal('iInvcDiscount', 15, 2)->nullable();
            $table->decimal('iInvcTax', 5, 2)->nullable();
            $table->decimal('iInvcShipping', 15, 2)->nullable();
            $table->decimal('yInvcTotalPayment', 15, 2);
            $table->timestamps();

            $table->foreign('iInvcComfk')->references('id')->on('companies');
            $table->foreign('iInvcCustDfk')->references('iCustDPk')->on('customer_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
