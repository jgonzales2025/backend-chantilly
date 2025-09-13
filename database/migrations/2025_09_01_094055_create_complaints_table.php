<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\text;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('number_complaint')->unique();
            $table->foreignId('local_id')->constrained('locals')->onDelete('cascade');
            $table->string('customer_name');
            $table->string('customer_lastname');
            $table->string('dni_ruc', 11);
            $table->text('address');
            $table->string('email');
            $table->string('phone', 9);
            $table->text('parent_data')->nullable();
            $table->enum('well_hired', ['Producto', 'Servicio']);
            $table->decimal('amount', 10, 2);
            $table->text('description');
            $table->enum('type_complaint', ['Reclamo', 'Queja']);
            $table->text('detail_complaint');
            $table->string('order');
            $table->date('date_complaint');
            $table->string('path_evidence')->nullable();
            $table->text('observations');
            $table->string('path_customer_signature')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
