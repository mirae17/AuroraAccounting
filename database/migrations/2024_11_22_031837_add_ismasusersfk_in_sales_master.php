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
        Schema::table('sales_master', function (Blueprint $table) {
              // Re-add the column
              $table->unsignedBigInteger('ismasusersfk')->nullable()->after('ismasinvoiceref');

              // Add the foreign key constraint
              $table->foreign('ismasusersfk')->references('iEmpmasPk')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_master', function (Blueprint $table) {

           
        });
    }
};
