@extends('user.layouts.app')

@section('title', 'My Requests')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">My Requests</h1>
    <p class="text-gray-600">Track all your donation requests</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Donation</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Original Stock</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Taken</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Remaining Stock</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-500">My Request</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Pickup Time</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($requests as $req)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-medium text-gray-900">{{ $req->donation->food_name ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $req->donation->category ?? '' }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $req->donation->original_stock ?? $req->donation->quantity ?? '-' }}
                        {{ $req->donation->unit->name ?? '' }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $req->donation->total_taken ?? 0 }}
                        {{ $req->donation->unit->name ?? '' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-medium text-[#2E7D32]">
                            {{ $req->donation->remaining_stock ?? $req->donation->remaining_quantity ?? $req->donation->quantity ?? '-' }}
                            {{ $req->donation->unit->name ?? '' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $req->requested_quantity ?? $req->portions }}
                        {{ $req->donation->unit->name ?? '' }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ \Carbon\Carbon::parse($req->pickup_time)->format('d M Y H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        <x-badge :status="$req->status" />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        No requests yet.
                        <a href="{{ route('user.donations.index') }}" class="text-[#2E7D32] hover:underline ml-1">Browse donations</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
