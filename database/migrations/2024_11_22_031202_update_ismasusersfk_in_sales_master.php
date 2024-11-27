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
            // Drop the existing foreign key and column
           
                $table->dropColumn('ismasusersfk');   // Drop the column
            

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_master', function (Blueprint $table) {
          
            $table->dropColumn('ismasusersfk');

        });
    }
};
