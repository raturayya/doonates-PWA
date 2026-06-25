<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ──────────────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@doonates.com'],
            [
                'name'               => 'Admin Doonates',
                'organization_name'  => 'Doonates HQ',
                'organization_type'  => null,
                'email_verified_at'  => now(),
                'password'           => Hash::make('password'),
                'status'             => User::STATUS_APPROVED,
                'role'               => 'admin',
            ]
        );

        // ── Contoh organisasi approved ─────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'resto@example.com'],
            [
                'name'               => 'Restoran Sejahtera',
                'organization_name'  => 'Restoran Sejahtera',
                'organization_type'  => 'Restaurant',
                'phone'              => '08123456789',
                'address'            => 'Jl. Merdeka No. 1, Jakarta',
                'email_verified_at'  => now(),
                'password'           => Hash::make('password'),
                'status'             => User::STATUS_APPROVED,
                'role'               => 'organization',
            ]
        );

        // ── Contoh organisasi pending ──────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'catering@example.com'],
            [
                'name'               => 'Catering Barokah',
                'organization_name'  => 'Catering Barokah',
                'organization_type'  => 'Catering',
                'phone'              => '08198765432',
                'address'            => 'Jl. Raya Purwokerto No. 5',
                'email_verified_at'  => now(),
                'password'           => Hash::make('password'),
                'status'             => User::STATUS_PENDING,
                'role'               => 'organization',
            ]
        );
    }
}