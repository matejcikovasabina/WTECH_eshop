<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('guest_mail', 50)->nullable();
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('payment_method_id');
            $table->unsignedBigInteger('delivery_method_id');
            $table->unsignedBigInteger('billing_address_id');
            $table->unsignedBigInteger('shipping_address_id');
            $table->decimal('total_price', 10, 2);
            $table->timestampTz('created_at')->useCurrent();
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->text('note')->nullable();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('status_id')
                ->references('id')
                ->on('order_status')
                ->onDelete('restrict');

            $table->foreign('payment_method_id')
                ->references('id')
                ->on('payment_methods')
                ->onDelete('restrict');

            $table->foreign('delivery_method_id')
                ->references('id')
                ->on('delivery_methods')
                ->onDelete('restrict');

            $table->foreign('billing_address_id')
                ->references('id')
                ->on('addresses')
                ->onDelete('restrict');

            $table->foreign('shipping_address_id')
                ->references('id')
                ->on('addresses')
                ->onDelete('restrict');

            $table->foreign('discount_id')
                ->references('id')
                ->on('discount')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};