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
            if (! Schema::hasColumn('orders', 'customer_first_name')) {
                $table->string('customer_first_name', 50)->nullable()->after('guest_mail');
            }

            if (! Schema::hasColumn('orders', 'customer_last_name')) {
                $table->string('customer_last_name', 50)->nullable()->after('customer_first_name');
            }

            if (! Schema::hasColumn('orders', 'customer_phone')) {
                $table->string('customer_phone', 30)->nullable()->after('customer_last_name');
            }
        });
    }

};
