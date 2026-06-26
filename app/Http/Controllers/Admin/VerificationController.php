<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AccountApproved;
use App\Services\PushNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VerificationController extends Controller
{
    /**
     * Daftar organisasi pending.
     */
    public function index(): View
    {
        $pending  = User::where('status', 'pending')
            ->whereIn('role', ['organization', 'user'])
            ->latest()->get();

        $approved = User::where('status', 'approved')
            ->whereIn('role', ['organization', 'user'])
            ->latest()->get();

        $rejected = User::where('status', 'rejected')
            ->whereIn('role', ['organization', 'user'])
            ->latest()->get();

        return view('admin.verification.index', compact('pending', 'approved', 'rejected'));
    }

    /**
     * Approve organisasi.
     */
    public function approve(User $user): RedirectResponse
    {
        $user->update(['status' => User::STATUS_APPROVED]);

        $user->notify(new AccountApproved());

        // Push notification to the approved user
        $isOrganization = $user->role === 'organization';
        $url            = $isOrganization ? route('dashboard') : route('user.dashboard');
        $body           = $isOrganization
            ? "Your organization \"{$user->organization_name}\" has been approved. You can now post donations."
            : "Your Doonates account has been approved. Start browsing available donations!";

        app(PushNotificationService::class)->sendToUser(
            $user,
            '🎉 Account Approved!',
            $body,
            $url
        );

        return back()->with('success', "Organisasi \"{$user->organization_name}\" berhasil diapprove.");
    }

    /**
     * Reject organisasi.
     */
    public function reject(User $user): RedirectResponse
    {
        $user->update(['status' => User::STATUS_REJECTED]);

        // Opsional: kirim notifikasi email ke organisasi
        // $user->notify(new OrganizationRejected());

        return back()->with('success', "Organisasi \"{$user->organization_name}\" telah direject.");
    }
}