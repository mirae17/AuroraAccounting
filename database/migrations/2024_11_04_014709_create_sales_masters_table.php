<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales_master', function (Blueprint $table) {
            $table->id('ismaspk');
            $table->date('dsmasdate')->nullable();
            $table->string('csmasdesc', 150)->default('');
            $table->decimal('ysmasdeposit', 15, 2)->default(0);
            $table->decimal('ysmaspayment', 15, 2)->default(0);
            $table->unsignedBigInteger('ismasPymtdfk')->default(0);
            $table->unsignedBigInteger('ismasSuppfk')->default(0);
            $table->string('ismasinvoiceref', 50)->default('');
            $table->unsignedBigInteger('ismasusersfk')->default(0);
            $table->timestamps();
    
            $table->foreign('ismasPymtdfk')->references('iPymtdPk')->on('payment_methods');
            $table->foreign('ismasSuppfk')->references('iSuppPk')->on('suppliers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_masters');
    }
};
