<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('address_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 15);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('address_types');
    }
};