@extends('user.layouts.app')

@section('title', 'Request Detail')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
<div class="mb-6">
    <a href="{{ route('user.requests.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-4">
        ← Back to My Requests
    </a>
    <h1 class="text-2xl font-bold text-gray-900">Request Detail</h1>
</div>

@if(session('success'))
<div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700">
    {{ session('error') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Left: Request Info --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
        <div class="flex items-center justify-between">
            <h2 class="text-gray-900 font-semibold text-lg">{{ $requestDonation->donation->food_name ?? '-' }}</h2>
            <x-badge :status="$requestDonation->status" />
        </div>

        <div class="space-y-4 text-sm">
            <div class="flex items-start gap-3">
                <span class="p-2 bg-green-100 rounded-lg text-base">🍱</span>
                <div>
                    <p class="text-gray-500">Category</p>
                    <p class="font-medium text-gray-900">{{ $requestDonation->donation->category ?? '-' }}</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <span class="p-2 bg-purple-100 rounded-lg text-base">📦</span>
                <div>
                    <p class="text-gray-500">Requested Quantity</p>
                    <p class="font-medium text-gray-900">
                        {{ $requestDonation->requested_quantity ?? $requestDonation->portions }}
                        {{ $requestDonation->donation->unit->name ?? '' }}
                    </p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <span class="p-2 bg-yellow-100 rounded-lg text-base">⏰</span>
                <div>
                    <p class="text-gray-500">Pickup Time</p>
                    <p class="font-medium text-gray-900">
                        {{ \Carbon\Carbon::parse($requestDonation->pickup_time)->format('d M Y H:i') }}
                    </p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <span class="p-2 bg-blue-100 rounded-lg text-base">🏢</span>
                <div>
                    <p class="text-gray-500">Requested By</p>
                    <p class="font-medium text-gray-900">{{ $requestDonation->organization_name }}</p>
                    <p class="text-gray-500">{{ $requestDonation->organization_type }}</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <span class="p-2 bg-orange-100 rounded-lg text-base">💬</span>
                <div>
                    <p class="text-gray-500">Message</p>
                    <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $requestDonation->message }}</p>
                </div>
            </div>
        </div>

        {{-- Picked Up Button --}}
        @if($requestDonation->status === 'Approved')
        <div class="pt-2 border-t border-gray-100">
            <form method="POST" action="{{ route('user.requests.pickedUp', $requestDonation->id) }}">
                @csrf
                @method('PATCH')
                <button type="submit"
                    class="w-full py-3 bg-[#2E7D32] text-white rounded-xl font-semibold hover:bg-[#25662a] transition-colors"
                    onclick="return confirm('Confirm you have picked up this donation?')">
                    ✅ Mark as Picked Up
                </button>
            </form>
        </div>
        @elseif($requestDonation->status === 'Finished')
        <div class="pt-2 border-t border-gray-100">
            <div class="w-full py-3 bg-gray-100 text-gray-600 rounded-xl text-center font-semibold">
                ✅ Picked Up
            </div>
        </div>
        @endif
    </div>

    {{-- Right: Map --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-4 border-b border-gray-200">
        <h2 class="font-semibold text-gray-900">📍 Pickup Location</h2>
    </div>

    @if($requestDonation->donation->hasLocation())
    <div id="user-map" style="height: 350px;"></div>
    <div class="p-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between gap-3">
        <p class="text-sm text-gray-600">
            {{ number_format($requestDonation->donation->pickup_latitude, 6) }},
            {{ number_format($requestDonation->donation->pickup_longitude, 6) }}
        </p>
        <a href="https://www.google.com/maps?q={{ $requestDonation->donation->pickup_latitude }},{{ $requestDonation->donation->pickup_longitude }}"
           target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition-colors">
            🗺️ Open in Google Maps
        </a>
    </div>
    @else
    <div class="flex flex-col items-center justify-center h-64 text-gray-400">
        <span class="text-4xl mb-3">📍</span>
        <p class="text-sm">Pickup location not set.</p>
        <p class="text-xs text-gray-400 mt-1">The organization has not set a pickup location yet.</p>
    </div>
    @endif
</div>

</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@if($requestDonation->donation->hasLocation())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lat = {{ $requestDonation->donation->pickup_latitude }};
        const lng = {{ $requestDonation->donation->pickup_longitude }};
        const map = L.map('user-map').setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        L.marker([lat, lng])
            .addTo(map)
            .bindPopup('Pickup Location')
            .openPopup();
    });
</script>
@endif
@endpush
@endsection
