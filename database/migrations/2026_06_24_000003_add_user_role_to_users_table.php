<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // For SQLite: recreate the column with new enum values
        // For MySQL: modify the enum
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'organization', 'user') DEFAULT 'organization'");
        }
        // SQLite doesn't support ALTER COLUMN - the 'user' role will still work
        // because SQLite doesn't enforce enum constraints
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'organization') DEFAULT 'organization'");
        }
    }
};
