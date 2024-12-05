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
        Schema::table('customer_details', function (Blueprint $table) {
            $table->string('iComp');
            $table->string('cCustDCompName');
            $table->string('cCustDCompNo', 11)->unique();
            $table->longText('cCustDCompOfficeNo');
            $table->string('cCustDCompEmail');
            $table->string('cCustDCompWebsite');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_details', function (Blueprint $table) {
            //
        });
    }
};
