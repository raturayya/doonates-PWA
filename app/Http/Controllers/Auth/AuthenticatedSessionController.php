<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses login.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        /** @var User $user */
        $user = Auth::user();

        // Blokir user yang statusnya pending
        if ($user->isPending()) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('pending');
        }

        // Blokir user yang statusnya rejected
        if ($user->isRejected()) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Akun Anda ditolak oleh admin. Hubungi support untuk informasi lebih lanjut.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        /**
         * Redirect berdasarkan role
         */
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'organization') {
            return redirect()->route('dashboard');
        }

        if ($user->role === 'user') {
            return redirect()->route('user.dashboard');
        }

        return redirect('/');
    }

    /**
     * Logout.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}