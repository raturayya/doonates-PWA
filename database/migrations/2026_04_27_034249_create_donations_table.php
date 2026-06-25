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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();

            // Informasi utama
            $table->string('food_name');
            $table->string('category');
            $table->string('portions');

            // Waktu
            $table->date('expiry_date');
            $table->dateTime('pickup_time');

            // Detail tambahan
            $table->text('description');

            // Status lifecycle donation
            $table->enum('status', [
                'Available',
                'Requested',
                'Approved',
                'Completed'
            ])->default('Available');

            // Relasi sederhana (sementara)
            $table->string('organization_name')->nullable();

            // Future-ready (opsional, untuk scaling)
            // $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            // $table->foreignId('organization_id')->nullable()->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};