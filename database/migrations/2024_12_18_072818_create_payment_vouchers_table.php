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
        Schema::create('payment_vouchers', function (Blueprint $table) {
            $table->id('iPymtVchrPk');
            $table->unsignedBigInteger('iPymtVchrPymtdfk');//type of bank 
            $table->string('cPymtVchrNo')->unique();//voucher_no Auto-generated and sequential
            $table->string('cPymtVchrDesc');//Purpose of the payment
            $table->date('dPymtVchrDate');//Date of payment
            $table->string('cPymtVchrNoAcc');//no acc of bank
            $table->string('cPymtVchrMethod');//payment_method (e.g., Cash, Bank Transfer, Cheque
            $table->string('cPymtVchrName');//Name of the person or organization being paid
            $table->string('yPymtVchrTotal');//Total payment amount
            $table->string('cPymtVchrRefNo');//Optional reference to related transactions like purchase orders or invoices
            $table->timestamps();

            $table->foreign('iPymtVchrPymtdfk')->references('iPymtdPk')->on('payments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_vouchers');
    }
};
