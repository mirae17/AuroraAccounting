<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::rename('payment_methods', 'payments');
    }
    
    public function down()
    {
        Schema::rename('payments', 'payment_methods');
    }
};
