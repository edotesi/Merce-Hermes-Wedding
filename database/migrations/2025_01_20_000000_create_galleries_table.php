<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('galleries');

        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // nombre del archivo
            $table->string('category'); // ceremonia, fiesta, preboda, etc.
            $table->string('path'); // ruta completa de la imagen original
            $table->string('thumbnail_path')->nullable(); // ruta del thumbnail
            $table->integer('width')->nullable(); // ancho original
            $table->integer('height')->nullable(); // alto original
            $table->integer('filesize')->nullable(); // tamaño en bytes
            $table->integer('order')->default(0); // para ordenar manualmente si es necesario
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
