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
        Schema::create('company_maintenances', function (Blueprint $table) {
            $table->id('iCompMainPk');
            $table->string('iCompMainName');
            $table->string('iCompMainRegNo')->unique();
            $table->longText('iCompMainAddress');
            $table->string('iCompMainPhoneNo')->unique();
            $table->string('iCompMainEmail');
            $table->string('iCompMainLogo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_maintenances');
    }
};
