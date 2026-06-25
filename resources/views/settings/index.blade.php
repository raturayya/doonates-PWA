@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6">
        <h1 class="text-gray-900 mb-2">Settings</h1>
        <p class="text-gray-600">Manage your account and preferences</p>
    </div>

    <div class="space-y-6">
        <!-- Account Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h2 class="text-gray-900">Account Settings</h2>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between py-3 border-b border-gray-200">
                    <div>
                        <p class="text-gray-900">Email Notifications</p>
                        <p class="text-sm text-gray-600">Receive email updates about donations and requests</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" checked />
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#2E7D32] rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#2E7D32]"></div>
                    </label>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-gray-200">
                    <div>
                        <p class="text-gray-900">Push Notifications</p>
                        <p class="text-sm text-gray-600">Get notified about new requests</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" />
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#2E7D32] rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#2E7D32]"></div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Security -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h2 class="text-gray-900">Security</h2>
            </div>
            <div class="space-y-4">
                <a href="#" class="block w-full text-left py-3 border-b border-gray-200 text-gray-900 hover:text-[#2E7D32]">
                    Change Password
                </a>
                <a href="#" class="block w-full text-left py-3 border-b border-gray-200 text-gray-900 hover:text-[#2E7D32]">
                    Two-Factor Authentication
                </a>
            </div>
        </div>

        <!-- Preferences -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-5 h-5 text-[#2E7D32]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-gray-900">Preferences</h2>
            </div>
            <div class="space-y-4">
                <div class="py-3 border-b border-gray-200">
                    <label class="block text-gray-700 mb-2">Language</label>
                    <select class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent">
                        <option>English</option>
                        <option>Spanish</option>
                        <option>French</option>
                    </select>
                </div>
                <div class="py-3 border-b border-gray-200">
                    <label class="block text-gray-700 mb-2">Timezone</label>
                    <select class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent">
                        <option>UTC-05:00 (Eastern Time)</option>
                        <option>UTC-06:00 (Central Time)</option>
                        <option>UTC-07:00 (Mountain Time)</option>
                        <option>UTC-08:00 (Pacific Time)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
