@extends('layouts.app')

@section('title', 'Organization Profile')

@section('content')
<div class="w-full">

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Organization Profile</h1>
        <p class="text-gray-600">Manage your organization information</p>
    </div>

    <div class="mx-auto w-full bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-8">

            <!-- HEADER -->
            <div class="flex items-start justify-between mb-6">
                <div class="flex items-center gap-4">

                    <!-- ICON -->
                    <div class="w-16 h-16 rounded-xl bg-[#2E7D32] flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>

                    <!-- NAME -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">
                            {{ $user->organization_name ?? $user->name }}
                        </h2>

                        <p class="text-gray-600">
                            {{ $user->organization_type ?? 'Organization' }}
                        </p>
                    </div>
                </div>

                <!-- ACTION -->
                <div class="flex items-center gap-3">

                    <!-- STATUS -->
                    <x-badge :status="$user->is_verified ?? false ? 'Verified' : 'Pending'" />

                    <!-- EDIT -->
                    <a href="{{ route('profile.edit') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-[#2E7D32] text-white rounded-lg hover:bg-[#25662a] transition">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>

                        Edit Profile
                    </a>
                </div>
            </div>

            <!-- INFO -->
            <div class="space-y-6">

                <!-- EMAIL & PHONE -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- EMAIL -->
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-gray-100 rounded-lg">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Email Address</p>
                            <p class="text-gray-900">
                                {{ $user->email }}
                            </p>
                        </div>
                    </div>

                    <!-- PHONE -->
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-gray-100 rounded-lg">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Phone Number</p>
                            <p class="text-gray-900">
                                {{ $user->phone ?? '-' }}
                            </p>
                        </div>
                    </div>

                </div>

                <!-- ADDRESS -->
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-gray-100 rounded-lg">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Address</p>
                        <p class="text-gray-900">
                            {{ $user->address ?? '-' }}
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection