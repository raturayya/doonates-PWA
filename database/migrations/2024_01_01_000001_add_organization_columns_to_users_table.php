<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom organisasi (jika belum ada)
            if (!Schema::hasColumn('users', 'organization_name')) {
                $table->string('organization_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'organization_type')) {
                $table->enum('organization_type', [
                    'Restaurant',
                    'Hotel',
                    'Catering',
                    'Social Institution',
                ])->nullable()->after('organization_name');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('organization_type');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])
                      ->default('pending')
                      ->after('address');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'organization'])
                      ->default('organization')
                      ->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'organization_name',
                'organization_type',
                'phone',
                'address',
                'status',
                'role',
            ]);
        });
    }
};