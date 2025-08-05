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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('short_description')->nullable();
            $table->string('large_description')->nullable();
            $table->foreignId('product_type_id')->constrained('product_types')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->decimal('min_price', 10, 2);
            $table->decimal('max_price', 10, 2);
            $table->foreignId('theme_id')->constrained('themes')->onDelete('cascade');
            $table->string('image_url')->nullable();
            $table->boolean('status')->default(0);
            $table->boolean('best_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
