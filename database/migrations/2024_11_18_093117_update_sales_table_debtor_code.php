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
            // Drop the 'cDebtorCode' column
            $table->dropColumn('cDebtorCode');
            
            // Add the 'csmasDebtorfk' column after 'ysmadeposit' and set it as a foreign key
            $table->unsignedBigInteger('csmasDebtorfk')->nullable()->after('ysmasdeposit');
            
            // Define the foreign key constraint with ON DELETE SET NULL
            $table->foreign('csmasDebtorfk')
                  ->references('iDebtorPk')
                  ->on('debtor')
                  ->onDelete('no action'); // Replace cascade with set null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_master', function (Blueprint $table) {
            // Drop the foreign key and column
            $table->dropForeign(['csmasDebtorfk']);
            $table->dropColumn('csmasDebtorfk');
            
            // Re-add the 'cDebtorCode' column if rolling back
            $table->string('cDebtorCode', 6)->default('')->after('ysmadeposit');
        });
    }
};
