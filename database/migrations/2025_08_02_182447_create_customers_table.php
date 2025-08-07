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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->string('lastname', 100)->nullable();
            $table->foreignId('id_document_type')->nullable()->constrained('document_types')->onDelete('cascade');
            $table->string('document_number', 50)->nullable()->unique();
            $table->string('email', 150)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 100)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('phone', 9)->nullable();
            $table->string('deparment', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->boolean('status')->default(1);
            $table->string('google_id')->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
