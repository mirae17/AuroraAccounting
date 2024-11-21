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
        Schema::create('expenses_master', function (Blueprint $table) {
            $table->id('iexmaspk');
            $table->date('dexmasdate')->nullable();
            $table->unsignedBigInteger('cexmasExpfk')->default(0); 
            $table->decimal('yexmaspayment', 15, 2)->default(0);
            $table->unsignedBigInteger('iexmasPymtdfk')->default(0);
            $table->string('iexmasinvoiceref', 50)->default('');
            $table->string('cexmasnotes', 150)->default('');

            $table->timestamps();
    
            $table->foreign('iexmasPymtdfk')->references('iPymtdPk')->on('payments');
            $table->foreign('cexmasExpfk')->references('iExpPk')->on('expenses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses_master');
    }
};
