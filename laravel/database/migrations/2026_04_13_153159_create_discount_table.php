<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discount', function (Blueprint $table) {
            $table->id();
            $table->string('name', 15);
            $table->string('code', 8)->unique();
            $table->date('valid_from');
            $table->date('valid_to');
            $table->decimal('value', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount');
    }
};