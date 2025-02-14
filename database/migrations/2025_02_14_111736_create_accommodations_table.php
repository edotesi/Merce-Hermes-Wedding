<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('accommodations', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('zone');
        $table->string('stars')->nullable();
        $table->float('distance', 4, 1);
        $table->string('website');
        $table->text('discount_info')->nullable();
        $table->boolean('is_farinera_group')->default(false);
        $table->integer('order')->default(0);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodations');
    }
};
