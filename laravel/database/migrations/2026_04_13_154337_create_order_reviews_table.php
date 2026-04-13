<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->unique();
            $table->integer('rating');
            $table->string('comment', 100);

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_reviews');
    }
};