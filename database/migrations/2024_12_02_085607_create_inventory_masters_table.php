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
        Schema::create('inventory_masters', function (Blueprint $table) {
            $table->id('iInvmasPk');
            $table->unsignedBigInteger('cInvmasInvCodefk');
            $table->unsignedBigInteger('cInvmasInvNamefk');
            $table->date('dInvmasDate');
            $table->unsignedBigInteger('cInvmasSuppfk');
            $table->bigInteger('iInvmasQuanIn');
            $table->bigInteger('iInvmasQuanOut');
            $table->decimal('yInvmasDeposit',15,2);
            $table->decimal('yInvmasPayment',15,2);
            $table->unsignedBigInteger('cInvmasPymtdfk');
            $table->string('cInvmasInvoice');
            $table->unsignedBigInteger('cInvmasEmpfk');
            $table->unsignedBigInteger('cInvmasCreditorfk');
            $table->unsignedBigInteger('cInvmasCompfk');
            $table->timestamps();


            $table->foreign('cInvmasInvCodefk')->references('iInvPK')->on('inventories');
            $table->foreign('cInvmasInvNamefk')->references('iInvPK')->on('inventories');
            $table->foreign('cInvmasPymtdfk')->references('iPymtdPk')->on('payments');
            $table->foreign('cInvmasEmpfk')->references('iEmpmasPk')->on('employees');
            $table->foreign('cInvmasSuppfk')->references('iSuppPk')->on('suppliers');
            $table->foreign('cInvmasCompfk')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_masters');
    }
};
