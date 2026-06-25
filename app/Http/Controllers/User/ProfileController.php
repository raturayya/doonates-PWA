<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('user.profile.index', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('user.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'              => 'required|string|max:255',
            'organization_name' => 'nullable|string|max:255',
            'organization_type' => 'nullable|string|max:255',
            'phone'             => 'nullable|string|max:20',
            'address'           => 'nullable|string',
        ]);

        $user->update($request->only([
            'name', 'organization_name', 'organization_type', 'phone', 'address'
        ]));

        return redirect()->route('user.profile.index')->with('success', 'Profile updated successfully');
    }

    public function changePassword()
    {
        return view('user.profile.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('user.profile.index')->with('success', 'Password changed successfully');
    }
}
