@extends('layouts.app')

@section('title', 'Organizations')

@section('content')
<div x-data="{ searchTerm: '', filterType: 'All' }">
    <div class="mb-6">
        <h1 class="text-gray-900">Organizations</h1>
        <p class="text-gray-600">Browse verified organizations in the Doonates network</p>
    </div>

    <!-- Search and Filter -->
    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        <div class="flex-1 relative">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input
                type="text"
                x-model="searchTerm"
                placeholder="Search organizations..."
                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent bg-white"
            />
        </div>

        <div class="relative">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            <select
                x-model="filterType"
                class="pl-10 pr-8 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent bg-white min-w-[200px]"
            >
                <option value="All">All Types</option>
                <option value="Restaurant">Restaurant</option>
                <option value="Hotel">Hotel</option>
                <option value="Catering">Catering</option>
                <option value="Social Institution">Social Institution</option>
            </select>
        </div>
    </div>

    <!-- Organizations Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($organizations ?? [] as $org)
        <a
            href="{{ route('organizations.show', $org->id) }}"
            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-[#2E7D32] transition-all group"
        >
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-[#2E7D32] flex items-center justify-center group-hover:bg-[#25662a] transition-colors">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <x-badge :status="$org->verification_status" />
            </div>

            <h3 class="text-gray-900 mb-2">{{ $org->name }}</h3>
            <p class="text-sm text-gray-600 mb-3">{{ $org->type }}</p>

            <div class="flex items-center gap-2 text-sm text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span>{{ $org->location }}</span>
            </div>
        </a>
        @empty
        <div class="col-span-full text-center py-12">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <p class="text-gray-500">No organizations found matching your criteria</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
