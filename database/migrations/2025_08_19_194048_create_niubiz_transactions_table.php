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
        Schema::create('niubiz_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('cascade');
            $table->string('purchase_number')->unique()->nullable(); // Número de compra único
            $table->string('session_token')->nullable(); // Token de sesión
            $table->string('token_id')->nullable(); // Token del formulario de pago
            $table->decimal('amount', 10, 2); // Monto en soles
            $table->string('currency', 3)->default('PEN');
            $table->enum('status', ['pending', 'success', 'failed', 'cancelled'])->default('pending');
            $table->string('transaction_id')->nullable(); // ID de transacción de Niubiz
            $table->string('action_code')->nullable(); // Código de respuesta de Niubiz
            $table->dateTime('transaction_date')->nullable(); // Fecha de transacción de Niubiz
            $table->integer('niubiz_code_http')->nullable(); // Código HTTP de respuesta de Niubiz
            $table->text('niubiz_response')->nullable(); // Respuesta completa de Niubiz
            $table->text('error_message')->nullable(); // Mensaje de error si falla
            $table->integer('retry_count')->default(0); // Contador de reintentos
            $table->timestamp('last_retry_at')->nullable(); // Último intento
            $table->timestamps();

            // Índices para optimizar consultas
            $table->index(['order_id', 'status']);
            $table->index('purchase_number');
            $table->index('transaction_id');
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('niubiz_transactions');
    }
};
