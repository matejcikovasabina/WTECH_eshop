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
            $table->foreignId('product_id')->primary()->constrained('products')->onDelete('cascade');       
            $table->text('description');
            $table->foreignId('cover_type_id')->constrained()->onDelete('cascade');

            $table->string('isbn')->nullable();
            $table->integer('year'); // Rok vydania

            $table->foreignId('publisher_id')->constrained()->onDelete('cascade');
            $table->foreignId('language_id')->constrained()->onDelete('cascade');

            $table->integer('pages_num')->nullable();

            $table->string('image_path')->nullable();
            $table->boolean('is_bestseller')->default(false); // badge bestseller
            $table->decimal('rating', 3, 2)->default(0);
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
