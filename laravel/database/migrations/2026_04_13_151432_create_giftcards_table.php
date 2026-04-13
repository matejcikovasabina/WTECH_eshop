<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('giftcards', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->primary();
            $table->integer('value');
            $table->string('code', 10)->unique();
            $table->timestampTz('expires_at');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('giftcards');
    }
};