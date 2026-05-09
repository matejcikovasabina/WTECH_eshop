<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'avatar')) {
            return;
        }

        DB::table('users')
            ->whereNull('avatar')
            ->orWhere('avatar', '')
            ->orWhere('avatar', 'avatar1.png')
            ->update(['avatar' => 'avatar1.jpg']);

        DB::statement("ALTER TABLE users ALTER COLUMN avatar SET DEFAULT 'avatar1.jpg'");
    }

    public function down(): void
    {
        if (!Schema::hasColumn('users', 'avatar')) {
            return;
        }

        DB::statement("ALTER TABLE users ALTER COLUMN avatar SET DEFAULT 'avatar1.png'");
    }
};
