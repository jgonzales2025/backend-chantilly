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
        Schema::create('cake_flavor_filling', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cake_flavor_id')->constrained('cake_flavors')->onDelete('cascade');
            $table->foreignId('filling_id')->constrained('fillings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cake_filling');
    }
};
