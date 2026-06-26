<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\DonationController as UserDonationController;
use App\Http\Controllers\User\RequestController as UserRequestController;
use App\Http\Controllers\User\ProfileController as UserProfileController;

Route::get('/', fn () => redirect()->route('login'));

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Organization Routes (non-admin users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'approved'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('donations', DonationController::class)
        ->only(['index', 'store', 'destroy']);
    Route::patch('/donations/{donation}/status', [DonationController::class, 'updateStatus'])
        ->name('donations.updateStatus');
    Route::get('/donations/{donation}', [DonationController::class, 'show'])->name('donations.show');
    Route::get('/donations/{donation}/edit', [DonationController::class, 'edit'])->name('donations.edit');
    Route::put('/donations/{donation}', [DonationController::class, 'update'])->name('donations.update');

    Route::get('/requests', [RequestController::class, 'index'])->name('requests.index');
    Route::patch('/requests/{requestDonation}/approve', [RequestController::class, 'approve'])->name('requests.approve');
    Route::patch('/requests/{requestDonation}/reject', [RequestController::class, 'reject'])->name('requests.reject');
    Route::patch('/requests/{requestDonation}/set-location', [RequestController::class, 'setLocation'])->name('requests.setLocation');
    Route::patch('/requests/{requestDonation}/picked-up', [RequestController::class, 'markPickedUp'])->name('requests.pickedUp');

    Route::get('/organizations', [OrganizationController::class, 'index'])->name('organizations.index');
    Route::get('/organizations/{organization}', [OrganizationController::class, 'show'])->name('organizations.show');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'approved'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/verification', [VerificationController::class, 'index'])->name('verification.index');
        Route::patch('/verification/{user}/approve', [VerificationController::class, 'approve'])->name('verification.approve');
        Route::patch('/verification/{user}/reject', [VerificationController::class, 'reject'])->name('verification.reject');
        Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    });

/*
|--------------------------------------------------------------------------
| User Routes (role: user)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'approved'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

        // Donations
        Route::get('/donations', [UserDonationController::class, 'index'])->name('donations.index');
        Route::get('/donations/{donation}', [UserDonationController::class, 'show'])->name('donations.show');
        Route::post('/donations/{donation}/request', [UserDonationController::class, 'request'])->name('donations.request');

        // My Requests
        Route::get('/requests', [UserRequestController::class, 'index'])->name('requests.index');
        Route::get('/requests/{requestDonation}', [UserRequestController::class, 'show'])->name('requests.show');
        Route::patch('/requests/{requestDonation}/picked-up', [UserRequestController::class, 'markPickedUp'])->name('requests.pickedUp');

        // Profile
        Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/change-password', [UserProfileController::class, 'changePassword'])->name('profile.change-password');
        Route::put('/profile/change-password', [UserProfileController::class, 'updatePassword'])->name('profile.update-password');
    });
