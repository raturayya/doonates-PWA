<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {

            // Tambah kolom baru
            $table->integer('quantity')->after('category');

            $table->foreignId('unit_id')
                ->after('quantity')
                ->constrained('units')
                ->cascadeOnDelete();

            // Hapus kolom lama
            $table->dropColumn('portions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {

            // Kembalikan kolom lama
            $table->string('portions')->after('category');

            // Hapus foreign key dulu
            $table->dropForeign(['unit_id']);

            // Hapus kolom baru
            $table->dropColumn([
                'quantity',
                'unit_id'
            ]);
        });
    }
};