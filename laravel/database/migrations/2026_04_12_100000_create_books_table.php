<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->foreignId('product_id')
                ->primary()
                ->constrained('products')
                ->onDelete('cascade');

            $table->string('isbn')->nullable()->unique();

            $table->foreignId('publisher_id')
                ->constrained('publishers')
                ->onDelete('cascade');

            $table->foreignId('language_id')
                ->constrained('languages')
                ->onDelete('cascade');

            $table->foreignId('binding_id')
                ->constrained('bindings')
                ->onDelete('cascade');

            $table->integer('year');
            $table->integer('pages_num')->nullable();

            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->decimal('depth', 8, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};