@extends('user.layouts.app')

@section('title', 'Request Donation')

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('user.donations.index') }}" class="text-gray-500 hover:text-gray-700">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ $donation->food_name }}</h1>
        <p class="text-gray-600">Request this donation</p>
    </div>
</div>

<div class="grid md:grid-cols-2 gap-6">
    <!-- Donation Info -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Donation Details</h2>

        <div class="space-y-3 text-sm">
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Food Name</span>
                <span class="font-medium">{{ $donation->food_name }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Category</span>
                <span class="font-medium">{{ $donation->category }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Donor</span>
                <span class="font-medium">{{ $donation->organization_name }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Expiry Date</span>
                <span class="font-medium">{{ \Carbon\Carbon::parse($donation->expiry_date)->format('d M Y') }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Pickup Time</span>
                <span class="font-medium">{{ \Carbon\Carbon::parse($donation->pickup_time)->format('d M Y H:i') }}</span>
            </div>
            <div class="py-2 border-b border-gray-100">
                <span class="text-gray-500">Description</span>
                <p class="mt-1 font-medium">{{ $donation->description }}</p>
            </div>
        </div>

        <!-- Stock Summary -->
        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Stock Information</h3>
            <div class="grid grid-cols-3 gap-3 text-center">
                <div class="bg-white p-3 rounded-lg border border-gray-200">
                    <p class="text-xs text-gray-500 mb-1">Original Stock</p>
                    <p class="font-bold text-gray-800">{{ $donation->original_stock ?? $donation->quantity }}</p>
                    <p class="text-xs text-gray-400">{{ $donation->unit->name ?? '' }}</p>
                </div>
                <div class="bg-white p-3 rounded-lg border border-gray-200">
                    <p class="text-xs text-gray-500 mb-1">Taken</p>
                    <p class="font-bold text-orange-600">{{ $donation->total_taken ?? 0 }}</p>
                    <p class="text-xs text-gray-400">{{ $donation->unit->name ?? '' }}</p>
                </div>
                <div class="bg-white p-3 rounded-lg border border-green-200 bg-green-50">
                    <p class="text-xs text-gray-500 mb-1">Remaining</p>
                    <p class="font-bold text-[#2E7D32]">{{ $donation->remaining_stock ?? $donation->remaining_quantity ?? $donation->quantity }}</p>
                    <p class="text-xs text-gray-400">{{ $donation->unit->name ?? '' }}</p>
                </div>
            </div>
        </div>

        <!-- Uptake History -->
        @if($donation->requests->where('status', 'Approved')->count() > 0)
        <div class="mt-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Uptake History</h3>
            <div class="space-y-2">
                @foreach($donation->requests->where('status', 'Approved') as $req)
                <div class="flex justify-between text-sm p-2 bg-gray-50 rounded-lg">
                    <span class="text-gray-600">{{ $req->organization_name }}</span>
                    <span class="font-medium text-gray-800">{{ $req->requested_quantity ?? $req->portions }} {{ $donation->unit->name ?? '' }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Request Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Submit Request</h2>

        @php
            $remaining = $donation->remaining_stock ?? $donation->remaining_quantity ?? $donation->quantity;
        @endphp

        @if($remaining <= 0)
            <div class="p-4 bg-gray-100 rounded-lg text-center text-gray-600">
                <p class="font-medium">This donation has been fully taken.</p>
            </div>
        @else
        <form action="{{ route('user.donations.request', $donation) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Quantity <span class="text-gray-400">(max: {{ $remaining }} {{ $donation->unit->name ?? '' }})</span>
                </label>
                <input type="number" name="requested_quantity"
                    min="1" max="{{ $remaining }}" required
                    value="{{ old('requested_quantity') }}"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32]"
                    placeholder="Enter quantity">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Pickup Time</label>
                <input type="datetime-local" name="pickup_time" required
                    value="{{ old('pickup_time') }}"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32]">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Message (Optional)</label>
                <textarea name="message" rows="3"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] resize-none"
                    placeholder="Add a note for the donor...">{{ old('message') }}</textarea>
            </div>

            <button type="submit"
                class="w-full py-3 bg-[#2E7D32] text-white rounded-lg hover:bg-[#25662a] font-medium transition-colors">
                Submit Request
            </button>
        </form>
        @endif
    </div>
</div>
@endsection
