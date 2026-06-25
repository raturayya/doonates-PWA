@extends('user.layouts.app')

@section('title', 'Available Donations')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Available Donations</h1>
    <p class="text-gray-600">Browse and request food donations</p>
</div>

<!-- Search -->
<form method="GET" class="mb-6">
    <div class="flex gap-4">
        <div class="flex-1 relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search donations..."
                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32]">
        </div>
        <button type="submit" class="px-6 py-2.5 bg-[#2E7D32] text-white rounded-lg hover:bg-[#25662a]">Search</button>
    </div>
</form>

<!-- Donations Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Food Name</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Category</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Original Stock</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Taken</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Remaining</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Expiry</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($donations as $donation)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $donation->food_name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $donation->category }}</td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $donation->original_stock ?? $donation->quantity }} {{ $donation->unit->name ?? '' }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $donation->total_taken ?? 0 }} {{ $donation->unit->name ?? '' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-semibold text-[#2E7D32]">
                            {{ $donation->remaining_stock ?? $donation->remaining_quantity ?? $donation->quantity }}
                            {{ $donation->unit->name ?? '' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($donation->expiry_date)->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('user.donations.show', $donation) }}"
                           class="inline-flex items-center px-3 py-1.5 bg-[#2E7D32] text-white text-sm rounded-lg hover:bg-[#25662a]">
                            Request
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">No donations available.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-200">
        {{ $donations->links() }}
    </div>
</div>
@endsection
