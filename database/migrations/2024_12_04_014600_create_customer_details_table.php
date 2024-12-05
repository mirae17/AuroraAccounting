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
        Schema::create('customer_details', function (Blueprint $table) {
            $table->id('iCustDPk');
            $table->string('cCustDName');
            $table->string('cCustDPhoneNo', 11)->unique();
            $table->longText('cCustDAddress');
            $table->string('cCustDCity');
            $table->string('cCustDState');
            $table->string('cCustDPostcode');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_details');
    }
};
