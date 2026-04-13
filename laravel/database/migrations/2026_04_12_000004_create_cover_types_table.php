<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bindings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Pevná, Brožovaná, E-kniha...
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bindings');
    }
};