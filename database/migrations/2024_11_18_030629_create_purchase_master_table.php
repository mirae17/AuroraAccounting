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
        Schema::create('purchase_master', function (Blueprint $table) {
            $table->id('ipmaspk');
            $table->date('dpmasdate')->nullable();
            $table->unsignedBigInteger('ipmasSuppfk')->default(0);
            $table->string('cpmascodeprod', 150)->default('');
            $table->decimal('ypmaspayment', 15, 2)->default(0);
            $table->decimal('ypmasdeposit', 15, 2)->default(0);
            $table->unsignedBigInteger('ipmasPymtdfk')->default(0);
            $table->string('ipmasinvoiceref', 50)->default('');
            $table->string('cpmasnotes', 150)->default('');

           
            $table->timestamps();
    
            $table->foreign('ipmasPymtdfk')->references('iPymtdPk')->on('payments');
            $table->foreign('ipmasSuppfk')->references('iSuppPk')->on('suppliers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_master');
    }
};
