<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('gifts');

        Schema::create('gifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->string('image_url');
            $table->string('product_url')->nullable();

            // Modificaciones en los campos de stock y estado
            $table->unsignedInteger('stock')->default(1);
            $table->unsignedInteger('reserved_stock')->default(0);
            $table->enum('status', ['available', 'reserved', 'purchased'])
                  ->default('available')
                  ->index(); // Añadido índice para mejorar rendimiento en búsquedas

            // Campos de reserva y compra
            $table->timestamp('reserved_at')->nullable();
            $table->timestamp('reservation_expires_at')->nullable();
            $table->timestamp('purchased_at')->nullable();

            // Información del comprador
            $table->string('purchaser_name')->nullable();
            $table->string('purchaser_email')->nullable();
            $table->string('unique_code')->nullable()->unique(); // Añadido unique para evitar códigos duplicados

            $table->timestamps(); // created_at y updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gifts');
    }
};
