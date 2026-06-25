@extends('user.layouts.app')

@section('title', 'Change Password')

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('user.profile.index') }}" class="text-gray-500 hover:text-gray-700">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Change Password</h1>
        <p class="text-gray-600">Update your account password</p>
    </div>
</div>

<div class="max-w-md bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <form action="{{ route('user.profile.update-password') }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
            <input type="password" name="current_password" required
                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32]">
            @error('current_password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
            <input type="password" name="password" required
                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32]">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
            <input type="password" name="password_confirmation" required
                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32]">
        </div>

        <div class="flex gap-3">
            <button type="submit" class="flex-1 py-3 bg-[#2E7D32] text-white rounded-lg hover:bg-[#25662a] font-medium">
                Update Password
            </button>
            <a href="{{ route('user.profile.index') }}"
               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
