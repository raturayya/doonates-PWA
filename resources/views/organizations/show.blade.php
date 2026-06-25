@extends('layouts.app')

@section('title', $organization->name ?? 'Organization Details')

@section('content')
<div class="max-w-5xl">
    <a
        href="{{ route('organizations.index') }}"
        class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-6"
    >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Organizations
    </a>

    <!-- Organization Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="p-8">
            <div class="flex items-start justify-between mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 rounded-xl bg-[#2E7D32] flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-gray-900 mb-1">{{ $organization->name ?? 'Green Valley Restaurant' }}</h1>
                        <p class="text-gray-600">{{ $organization->type ?? 'Restaurant' }}</p>
                    </div>
                </div>
                <x-badge :status="$organization->verification_status ?? 'Verified'" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-gray-100 rounded-lg">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Email</p>
                        <p class="text-gray-900">{{ $organization->email ?? 'contact@greenvalley.com' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="p-2 bg-gray-100 rounded-lg">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Phone</p>
                        <p class="text-gray-900">{{ $organization->phone ?? '+1 (555) 123-4567' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="p-2 bg-gray-100 rounded-lg">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Location</p>
                        <p class="text-gray-900">{{ $organization->address ?? '123 Main Street, Springfield, IL' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Donations -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-8">
            <h2 class="text-gray-900 mb-6">Available Donations</h2>

            <div class="space-y-4">
                @forelse($donations ?? [] as $donation)
                <div class="border border-gray-200 rounded-lg p-5 hover:border-[#2E7D32] transition-colors">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-5 h-5 text-[#2E7D32]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-gray-900 mb-1">{{ $donation->food_name }}</h3>
                                <p class="text-sm text-gray-600">{{ $donation->description }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('donations.request', $donation->id) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-[#2E7D32] text-white rounded-lg hover:bg-[#25662a] transition-colors whitespace-nowrap">
                                Request
                            </button>
                        </form>
                    </div>

                    <div class="flex gap-6 text-sm text-gray-600 ml-11">
                        <div>
                            <span class="text-gray-500">Quantity:</span> {{ $donation->portions }}
                        </div>
                        <div>
                            <span class="text-gray-500">Best before:</span> {{ $donation->expiry_date }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <p class="text-gray-500">No donations available at the moment</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
