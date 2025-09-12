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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('order_number')->unique()->nullable();
            $table->enum('voucher_type', ['Boleta', 'Factura']);
            $table->array('billing_data')->nullable();
            $table->foreignId('local_id')->constrained('locals')->onDelete('cascade');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->dateTime('order_date')->nullable();
            $table->boolean('status')->default(1);
            $table->string('payment_method')->nullable()->default('Niubiz');
            $table->enum('payment_status', ['Pendiente', 'Pagado', 'Fallido'])->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->date('delivery_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
