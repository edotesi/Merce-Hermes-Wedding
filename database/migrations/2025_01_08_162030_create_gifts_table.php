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
            $table->integer('stock')->default(1);
            $table->integer('reserved_stock')->default(0);
            $table->enum('status', ['available', 'reserved', 'purchased'])->default('available');
            $table->timestamp('reserved_at')->nullable();
            $table->timestamp('reservation_expires_at')->nullable();
            $table->string('purchaser_name')->nullable();
            $table->string('purchaser_email')->nullable();
            $table->string('store')->nullable();
            $table->string('order_number')->nullable();
            $table->string('unique_code')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gifts');
    }
};
