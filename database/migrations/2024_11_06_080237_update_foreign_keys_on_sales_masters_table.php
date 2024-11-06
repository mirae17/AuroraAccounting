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
            // Drop existing foreign key constraints
            $table->dropForeign(['ismasPymtdfk']);
            $table->dropForeign(['ismasSuppfk']);
        

            // Re-add the foreign keys with updated settings
            $table->foreign('ismasPymtdfk')->references('iPymtdPk')->on('payment_methods')->onDelete('cascade');
            $table->foreign('ismasSuppfk')->references('iSuppPk')->on('suppliers')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_master', function (Blueprint $table) {
            // Drop the updated foreign key constraints
            $table->dropForeign(['ismasPymtdfk']);
            $table->dropForeign(['ismasSuppfk']);
            $table->dropForeign(['ismasusersfk']);

            // Re-add the original foreign keys if needed (optional)
            $table->foreign('ismasPymtdfk')->references('iPymtdPk')->on('payment_methods');
            $table->foreign('ismasSuppfk')->references('iSuppPk')->on('suppliers');
            $table->foreign('ismasusersfk')->references('id')->on('users');
        });
    }
};
