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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->text('description');
            $table->decimal('price', 8, 2);
            $table->string('genre');
            $table->string('cover_type'); // Väzba
            $table->string('dimensions')->nullable(); // Rozmer
            $table->string('isbn')->nullable();
            $table->integer('year'); // Rok vydania
            $table->string('publisher'); // Vydavatelstvo
            $table->string('language')->default('Slovenčina');
            $table->integer('stock')->default(0);
            $table->string('image_path')->nullable();
            $table->boolean('is_bestseller')->default(false); // Ten badge "Bestseller"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
