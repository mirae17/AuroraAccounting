<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('purchase_master', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable()->after('id'); // Add the company_id column
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade'); // Add foreign key
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
   
            Schema::table('purchase_master', function (Blueprint $table) {
                $table->dropForeign(['company_id']); // Drop foreign key constraint
                $table->dropColumn('company_id');   // Drop the company_id column
            });
        
    }
};
