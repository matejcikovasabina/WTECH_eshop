<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('street_name', 20);
            $table->string('city', 15);
            $table->string('zip_code', 6);
            $table->string('state', 20);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('address_type_id');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('address_type_id')
                ->references('id')
                ->on('address_types')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};