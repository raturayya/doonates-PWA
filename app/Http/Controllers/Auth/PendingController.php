<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PendingController extends Controller
{
    /**
     * Show the pending approval page.
     * Passes the authenticated user's data if logged in,
     * or falls back to session data after fresh registration.
     */
    public function show(Request $request): View
    {
        $user = Auth::user();

        return view('auth.pending', compact('user'));
    }

    /**
     * Re-check the authenticated user's approval status.
     * If now approved, redirect to their dashboard.
     * If still pending or rejected, stay on the pending page.
     */
    public function check(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Refresh the model from DB to get the latest status
        $user->refresh();

        if ($user->isApproved()) {
            // Regenerate session for security then redirect to correct dashboard
            $request->session()->regenerate();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Your account has been approved. Welcome!');
            }

            if ($user->role === 'organization') {
                return redirect()->route('dashboard')
                    ->with('success', 'Your account has been approved. Welcome!');
            }

            return redirect()->route('user.dashboard')
                ->with('success', 'Your account has been approved. Welcome!');
        }

        if ($user->isRejected()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Your account has been rejected by the admin. Please contact admin@doonates.com for more information.']);
        }

        // Still pending
        return redirect()->route('pending')
            ->with('status_checked', true);
    }
}
