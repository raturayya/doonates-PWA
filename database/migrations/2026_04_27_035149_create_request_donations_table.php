<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_donations', function (Blueprint $table) {
            $table->id();

            // Relasi ke donation
            $table->foreignId('donation_id')->constrained()->cascadeOnDelete();

            // Data organisasi requester
            $table->string('organization_name');
            $table->string('organization_type');

            // Detail request
            $table->integer('portions');
            $table->dateTime('pickup_time');
            $table->text('message');

            // Status workflow
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])
                  ->default('Pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_donations');
    }
};