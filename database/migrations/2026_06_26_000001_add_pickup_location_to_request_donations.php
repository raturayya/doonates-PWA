<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('request_donations', function (Blueprint $table) {
            $table->decimal('pickup_latitude', 10, 7)->nullable()->after('status');
            $table->decimal('pickup_longitude', 10, 7)->nullable()->after('pickup_latitude');
        });

        // Extend enum to include 'Finished'
        DB::statement("ALTER TABLE request_donations MODIFY COLUMN status ENUM('Pending','Approved','Rejected','Finished') NOT NULL DEFAULT 'Pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE request_donations MODIFY COLUMN status ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending'");

        Schema::table('request_donations', function (Blueprint $table) {
            $table->dropColumn(['pickup_latitude', 'pickup_longitude']);
        });
    }
};
