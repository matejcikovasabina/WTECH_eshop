<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE users ALTER COLUMN first_name TYPE VARCHAR(50)');
        DB::statement('ALTER TABLE users ALTER COLUMN last_name TYPE VARCHAR(50)');
        DB::statement('ALTER TABLE users ALTER COLUMN email TYPE VARCHAR(100)');
        DB::statement('ALTER TABLE users ALTER COLUMN phone TYPE VARCHAR(30)');

        DB::statement('ALTER TABLE addresses ALTER COLUMN street_name TYPE VARCHAR(100)');
        DB::statement('ALTER TABLE addresses ALTER COLUMN city TYPE VARCHAR(50)');
        DB::statement('ALTER TABLE addresses ALTER COLUMN zip_code TYPE VARCHAR(10)');

        DB::statement('ALTER TABLE orders ALTER COLUMN guest_mail TYPE VARCHAR(100)');

        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_first_name', 50)->nullable()->after('guest_mail');
            $table->string('customer_last_name', 50)->nullable()->after('customer_first_name');
            $table->string('customer_phone', 30)->nullable()->after('customer_last_name');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'customer_first_name',
                'customer_last_name',
                'customer_phone',
            ]);
        });

        DB::statement('ALTER TABLE users ALTER COLUMN first_name TYPE VARCHAR(10)');
        DB::statement('ALTER TABLE users ALTER COLUMN last_name TYPE VARCHAR(20)');
        DB::statement('ALTER TABLE users ALTER COLUMN email TYPE VARCHAR(50)');
        DB::statement('ALTER TABLE users ALTER COLUMN phone TYPE VARCHAR(255)');

        DB::statement('ALTER TABLE addresses ALTER COLUMN street_name TYPE VARCHAR(20)');
        DB::statement('ALTER TABLE addresses ALTER COLUMN city TYPE VARCHAR(15)');
        DB::statement('ALTER TABLE addresses ALTER COLUMN zip_code TYPE VARCHAR(6)');

        DB::statement('ALTER TABLE orders ALTER COLUMN guest_mail TYPE VARCHAR(50)');
    }
};
