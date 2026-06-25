@extends('user.layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
    <p class="text-gray-600">Welcome back, {{ auth()->user()->name }}!</p>
</div>

<div class="grid md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-[#2E7D32]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Available Donations</p>
                <p class="text-3xl font-bold text-gray-900">{{ $availableDonations }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">My Requests</p>
                <p class="text-3xl font-bold text-gray-900">{{ $myRequests }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Approved Requests</p>
                <p class="text-3xl font-bold text-gray-900">{{ $approvedRequests }}</p>
            </div>
        </div>
    </div>
</div>

<div>
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Available Donations</h2>
    <div class="grid md:grid-cols-3 gap-4">
        @forelse($recentDonations as $donation)
        <div class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-3">
                <h3 class="font-semibold text-gray-900">{{ $donation->food_name }}</h3>
                <x-badge :status="$donation->status" />
            </div>
            <div class="space-y-1 text-sm text-gray-600 mb-4">
                <p><span class="text-gray-400">Original Stock:</span> {{ $donation->original_stock ?? $donation->quantity }} {{ $donation->unit->name ?? '' }}</p>
                <p><span class="text-gray-400">Taken:</span> {{ $donation->total_taken ?? 0 }} {{ $donation->unit->name ?? '' }}</p>
                <p class="font-medium text-[#2E7D32]"><span class="text-gray-400">Remaining:</span> {{ $donation->remaining_stock ?? $donation->remaining_quantity ?? $donation->quantity }} {{ $donation->unit->name ?? '' }}</p>
            </div>
            <a href="{{ route('user.donations.show', $donation) }}"
               class="block w-full text-center px-4 py-2 bg-[#2E7D32] text-white rounded-lg hover:bg-[#25662a] transition-colors text-sm">
                Request
            </a>
        </div>
        @empty
        <div class="col-span-3 text-center py-12 text-gray-500">No available donations at the moment.</div>
        @endforelse
    </div>
</div>
@endsection
