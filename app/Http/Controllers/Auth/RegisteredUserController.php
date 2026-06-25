<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan halaman register (tab di dalam login page).
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses registrasi organisasi baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'organization_name' => ['required', 'string', 'max:255'],
            'organization_type' => ['required', 'in:Restaurant,Hotel,Catering,Social Institution'],
            'phone'             => ['required', 'string', 'max:20'],
            'address'           => ['required', 'string', 'max:500'],
            'email'             => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password'          => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'organization_name.required' => 'Nama organisasi wajib diisi.',
            'organization_type.required' => 'Tipe organisasi wajib dipilih.',
            'organization_type.in'       => 'Tipe organisasi tidak valid.',
            'phone.required'             => 'Nomor telepon wajib diisi.',
            'address.required'           => 'Alamat wajib diisi.',
            'email.required'             => 'Email wajib diisi.',
            'email.unique'               => 'Email sudah terdaftar.',
            'password.required'          => 'Password wajib diisi.',
            'password.confirmed'         => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'name'              => $request->organization_name, // gunakan nama org sebagai name
            'organization_name' => $request->organization_name,
            'organization_type' => $request->organization_type,
            'phone'             => $request->phone,
            'address'           => $request->address,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'status'            => User::STATUS_PENDING,  // default: menunggu verifikasi
            'role'              => 'organization',
        ]);

        event(new Registered($user));

        // TIDAK langsung login — arahkan ke halaman pending
        return redirect()->route('pending');
    }
}