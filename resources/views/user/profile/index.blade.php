@extends('user.layouts.app')

@section('title', 'Profile')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Profile</h1>
    <p class="text-gray-600">Manage your account information</p>
</div>

<div class="grid md:grid-cols-3 gap-6">
    <!-- Profile Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
        <div class="w-20 h-20 bg-[#2E7D32]/10 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-[#2E7D32]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
        <p class="text-gray-500 text-sm mt-1">{{ $user->email }}</p>
        <p class="text-gray-400 text-xs mt-1">{{ $user->organization_type ?? 'User' }}</p>

        <div class="mt-6 space-y-2">
            <a href="{{ route('user.profile.edit') }}"
               class="block w-full px-4 py-2 bg-[#2E7D32] text-white rounded-lg hover:bg-[#25662a] text-sm transition-colors">
                Edit Profile
            </a>
            <a href="{{ route('user.profile.change-password') }}"
               class="block w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm transition-colors">
                Change Password
            </a>
        </div>
    </div>

    <!-- Profile Details -->
    <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h2>
        <div class="space-y-4 text-sm">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-500 mb-1">Full Name</p>
                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">Email</p>
                    <p class="font-medium text-gray-900">{{ $user->email }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-500 mb-1">Organization Name</p>
                    <p class="font-medium text-gray-900">{{ $user->organization_name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">Organization Type</p>
                    <p class="font-medium text-gray-900">{{ $user->organization_type ?? '-' }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-500 mb-1">Phone</p>
                    <p class="font-medium text-gray-900">{{ $user->phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">Member Since</p>
                    <p class="font-medium text-gray-900">{{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>
            <div>
                <p class="text-gray-500 mb-1">Address</p>
                <p class="font-medium text-gray-900">{{ $user->address ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
