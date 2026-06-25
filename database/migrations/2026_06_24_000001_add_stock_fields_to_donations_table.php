<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->integer('original_stock')->nullable()->after('quantity');
            $table->integer('remaining_stock')->nullable()->after('original_stock');
            $table->integer('total_taken')->default(0)->after('remaining_stock');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn(['original_stock', 'remaining_stock', 'total_taken']);
        });
    }
};
