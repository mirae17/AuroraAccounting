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
                $table->string('cara_jualan')->nullable()->after('ismasPymtdfk'); // Replace 'column_name' with the existing column after which this field should appear
            });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_master', function (Blueprint $table) {
            $table->dropColumn('cara_jualan');
        });
    }
};
