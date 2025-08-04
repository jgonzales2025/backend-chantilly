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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('businnes_name');
            $table->string('ruc', 11);
            $table->string('number_whatsapp', 9)->nullable();
            $table->string('number_whatsapp1', 9)->nullable();
            $table->string('about_us', 100)->nullable();
            $table->string('facebook', 150)->nullable();
            $table->string('instagram', 150)->nullable();
            $table->string('twitter', 150)->nullable();
            $table->string('tiktok', 150)->nullable();
            $table->string('logo_header')->nullable();
            $table->string('logo_footer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
