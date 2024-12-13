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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id('iRecptPk');
            $table->unsignedBigInteger('iRecptComfk');
            $table->unsignedBigInteger('iRecptCustDfk');
            $table->string('iRecptNo')->unique();
            $table->date('dRecptdate');
            $table->decimal('yRecptSubtotal', 15, 2);
            $table->decimal('iRecptDiscount', 15, 2)->nullable();
            $table->decimal('iRecptTax', 5, 2)->nullable();
            $table->decimal('iRecptShipping', 15, 2)->nullable();
            $table->decimal('yRecptTotalPayment', 15, 2);
            $table->timestamps();

            $table->foreign('iRecptComfk')->references('id')->on('companies');
            $table->foreign('iRecptCustDfk')->references('iCustDPk')->on('customer_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
