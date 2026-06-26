<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $type = $request->input('register_type', 'organization');

        if ($type === 'organization') {
            $request->validate([
                'organization_name' => ['required', 'string', 'max:255'],
                'organization_type' => ['required', 'in:Restaurant,Hotel,Catering,Social Institution'],
                'phone'             => ['required', 'string', 'max:20'],
                'address'           => ['required', 'string', 'max:500'],
                'email'             => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
                'password'          => ['required', 'confirmed', Rules\Password::defaults()],
            ], [
                'organization_name.required' => 'Organization name is required.',
                'organization_type.required' => 'Organization type is required.',
                'organization_type.in'       => 'Invalid organization type.',
                'phone.required'             => 'Phone number is required.',
                'address.required'           => 'Address is required.',
                'email.required'             => 'Email is required.',
                'email.unique'               => 'This email is already registered.',
                'password.required'          => 'Password is required.',
                'password.confirmed'         => 'Passwords do not match.',
            ]);

            $user = User::create([
                'name'              => $request->organization_name,
                'organization_name' => $request->organization_name,
                'organization_type' => $request->organization_type,
                'phone'             => $request->phone,
                'address'           => $request->address,
                'email'             => $request->email,
                'password'          => Hash::make($request->password),
                'status'            => User::STATUS_PENDING,
                'role'              => 'organization',
            ]);
        } else {
            // Individual user registration
            $request->validate([
                'name'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ], [
                'name.required'     => 'Full name is required.',
                'email.required'    => 'Email is required.',
                'email.unique'      => 'This email is already registered.',
                'password.required' => 'Password is required.',
                'password.confirmed'=> 'Passwords do not match.',
            ]);

            $user = User::create([
                'name'     => $request->name,
                'phone'    => $request->user_phone,
                'address'  => $request->user_address,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'status'   => User::STATUS_PENDING,
                'role'     => 'user',
            ]);
        }

        event(new Registered($user));

        return redirect()->route('pending')->with('registered_email', $user->email);
    }
}
