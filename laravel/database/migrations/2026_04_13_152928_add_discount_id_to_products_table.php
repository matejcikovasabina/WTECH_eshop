<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('discount_id')->nullable()->after('stock_count');

            $table->foreign('discount_id')
                ->references('id')
                ->on('product_discounts')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['discount_id']);
            $table->dropColumn('discount_id');
        });
    }
};