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
        Schema::create('locals', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('image', 100)->nullable();
            $table->string('address', 200)->nullable();
            $table->string('department')->nullable();
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('link_local')->nullable();
            $table->decimal('latitud', 12, 10)->nullable();
            $table->decimal('longitud', 12, 10)->nullable();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locals');
    }
};
