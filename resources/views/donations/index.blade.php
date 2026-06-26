@extends('layouts.app')

@section('title', 'Manage Donations')

@section('content')
<div x-data="{
    showModal:false,
    showDetailModal:false,
    showEditModal:false,
    selectedDonation:null
}">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-gray-900 mb-2">Manage Donations</h1>
            <p class="text-gray-600">Track and manage your food donations</p>
        </div>
        <button
            @click="showModal = true; $nextTick(() => $dispatch('donation-modal-opened'))"
            class="inline-flex items-center gap-2 px-6 py-3 bg-[#2E7D32] text-white rounded-lg hover:bg-[#25662a] transition-colors"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Donation
        </button>
    </div>

    <!-- Search and Filter -->
    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        <div class="flex-1 relative">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input
                type="text"
                placeholder="Search donations..."
                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent bg-white"
            />
        </div>

        <div class="relative">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            <select class="pl-10 pr-8 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent bg-white min-w-[180px]">
                <option value="">All Status</option>
                <option value="Available">Available</option>
                <option value="Requested">Requested</option>
                <option value="Approved">Approved</option>
                <option value="Completed">Completed</option>
            </select>
        </div>
    </div>

    <!-- Donations Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Food Name</th>
                        <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Original</th>
                        <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Taken</th>
                        <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Remaining</th>
                        <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Organization</th>
                        <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Pickup Time</th>
                        <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($donations ?? [] as $donation)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-gray-900">{{ $donation->food_name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $donation->original_stock ?? $donation->quantity }} {{ $donation->unit->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $donation->total_taken ?? 0 }} {{ $donation->unit->name }}</td>
                        <td class="px-6 py-4 font-medium text-[#2E7D32]">{{ $donation->remaining_stock ?? $donation->remaining_quantity ?? $donation->quantity }} {{ $donation->unit->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $donation->organization_name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $donation->pickup_time }}</td>
                        <td class="px-6 py-4"><x-badge :status="$donation->status" /></td>
                        <td class="px-6 py-4">
    <div x-data="{ open: false }" class="relative">

        <button
            @click="open = !open"
            class="p-1 text-gray-400 hover:text-gray-600 rounded"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                </path>
            </svg>
        </button>

        <div
            x-show="open"
            @click.away="open = false"
            x-transition
            class="absolute right-0 mt-2 w-44 bg-white border border-gray-200 rounded-lg shadow-lg z-50"
        >

            <button
                @click="
                    selectedDonation = {
                    id: {{ $donation->id }},
                    food_name: '{{ $donation->food_name }}',
                    category: '{{ $donation->category }}',
                    quantity: {{ $donation->quantity }},
                    unit_name: '{{ $donation->unit->name }}',
                    organization_name: '{{ $donation->organization_name }}',
                    expiry_date: '{{ $donation->expiry_date }}',
                    pickup_time: '{{ $donation->pickup_time }}',
                    description: `{{ $donation->description }}`,
                    // Add these two lines inside the selectedDonation = { ... } object:
                    pickup_latitude: {{ $donation->pickup_latitude ?? 'null' }},
                    pickup_longitude: {{ $donation->pickup_longitude ?? 'null' }}
                };
                    showDetailModal = true;
                    open = false;
                "
                class="w-full text-left px-4 py-2 hover:bg-gray-50"
            >
                👁 View Detail
            </button>

<button
    @click="
    selectedDonation = {
        id: {{ $donation->id }},
        food_name: '{{ $donation->food_name }}',
        category: '{{ $donation->category }}',
        quantity: {{ $donation->quantity }},
        unit_id: {{ $donation->unit_id }},
        expiry_date: '{{ $donation->expiry_date }}',
        pickup_time: '{{ \Carbon\Carbon::parse($donation->pickup_time)->format('Y-m-d\TH:i') }}',
        description: `{{ $donation->description }}`
    };
        showEditModal = true;
        open = false;
    "
    class="w-full text-left px-4 py-2 hover:bg-gray-50"
>
    ✏️ Edit
</button>

            <form
                action="{{ route('donations.destroy', $donation->id) }}"
                method="POST"
                onsubmit="return confirm('Delete this donation?')"
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50"
                >
                    🗑 Delete
                </button>
            </form>

        </div>
    </div>
</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            No donations found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            <p class="text-sm text-gray-600">
                Showing {{ $donations->firstItem() ?? 0 }} to {{ $donations->lastItem() ?? 0 }} of {{ $donations->total() ?? 0 }} donations
            </p>
            <div class="flex gap-2">
                <button class="px-4 py-2 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">
                    Previous
                </button>
                <button class="px-4 py-2 bg-[#2E7D32] text-white rounded-lg hover:bg-[#25662a]">
                    Next
                </button>
            </div>
        </div>
    </div>

    <div
    x-show="showDetailModal"
    x-transition
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
>

    <div
        class="bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6"
        @click.away="showDetailModal = false"
    >

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold">
                Donation Details
            </h2>

            <button
                @click="showDetailModal = false"
                class="text-gray-400 hover:text-gray-600"
            >
                ✕
            </button>
        </div>

        <div class="grid md:grid-cols-2 gap-5">

            <div>
                <p class="text-sm text-gray-500">Food Name</p>
                <p class="font-medium" x-text="selectedDonation?.food_name"></p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Category</p>
                <p class="font-medium" x-text="selectedDonation?.category"></p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Portions</p>
                <p class="font-medium">
    <span x-text="selectedDonation?.quantity"></span>
    <span x-text="selectedDonation?.unit_name"></span>
</p>
</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Organization</p>
                <p class="font-medium" x-text="selectedDonation?.organization_name"></p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Expiry Date</p>
                <p class="font-medium" x-text="selectedDonation?.expiry_date"></p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Pickup Time</p>
                <p class="font-medium" x-text="selectedDonation?.pickup_time"></p>
            </div>

            <div class="md:col-span-2">
                <p class="text-sm text-gray-500">Description</p>
                <p class="font-medium" x-text="selectedDonation?.description"></p>
            </div>

        </div>

    </div>
</div>

<!-- Edit Donation Modal -->
<div
    x-show="showEditModal"
    x-transition
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
>
    <div
        class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto"
        @click.away="showEditModal = false"
    >

        <div class="flex items-center justify-between p-6 border-b">
            <h2 class="text-xl font-semibold">
                Edit Donation
            </h2>

            <button
                @click="showEditModal = false"
                class="text-gray-400 hover:text-gray-600"
            >
                ✕
            </button>
        </div>

        <form
            :action="'/donations/' + selectedDonation.id"
            method="POST"
            class="p-6 space-y-5"
        >
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-gray-700 mb-2">
                        Food Name
                    </label>

                    <input
                        type="text"
                        name="food_name"
                        x-model="selectedDonation.food_name"
                        required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg"
                    >
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">
                        Category
                    </label>

                    <select
                        name="category"
                        x-model="selectedDonation.category"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg"
                    >
                        <option value="Vegetables">Vegetables</option>
                        <option value="Fruits">Fruits</option>
                        <option value="Prepared Meals">Prepared Meals</option>
                        <option value="Baked Goods">Baked Goods</option>
                        <option value="Dairy">Dairy Products</option>
                        <option value="Packaged">Packaged Foods</option>
                    </select>
                </div>

            </div>

            <div class="grid md:grid-cols-2 gap-5">


        <div>
    <label class="block text-gray-700 mb-2">
        Expiry Date
    </label>

    <input
        type="date"
        name="expiry_date"
        x-model="selectedDonation.expiry_date"
        required
        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg"
    >
</div>

    <!-- Quantity -->
    <div>
        <label class="block text-gray-700 mb-2">
            Quantity
        </label>

        <input
            type="number"
            min="1"
            name="quantity"
            x-model="selectedDonation.quantity"
            required
            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg"
        >
    </div>

    <!-- Unit -->
    <div>
        <label class="block text-gray-700 mb-2">
            Unit
        </label>

        <select
            name="unit_id"
            x-model="selectedDonation.unit_id"
            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg"
        >
            @foreach($units as $unit)
                <option value="{{ $unit->id }}">
                    {{ $unit->name }}
                </option>
            @endforeach
        </select>
    </div>

</div>

            <div>
                <label class="block text-gray-700 mb-2">
                    Pickup Time
                </label>

                <input
                    type="datetime-local"
                    name="pickup_time"
                    x-model="selectedDonation.pickup_time"
                    required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg"
                >
            </div>

            <div>
                <label class="block text-gray-700 mb-2">
                    Description
                </label>

                <textarea
                    name="description"
                    rows="4"
                    x-model="selectedDonation.description"
                    required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg resize-none"
                ></textarea>
            </div>

            {{-- Pickup Location (Edit) --}}
<div
    x-data="{
        lat: selectedDonation.pickup_latitude ? parseFloat(selectedDonation.pickup_latitude) : null,
        lng: selectedDonation.pickup_longitude ? parseFloat(selectedDonation.pickup_longitude) : null,
        map: null,
        marker: null,
        initMap() {
            if (this.map) return;
            const center = (this.lat && this.lng) ? [this.lat, this.lng] : [-6.2088, 106.8456];
            this.map = L.map('edit-donation-map').setView(center, this.lat ? 15 : 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(this.map);
            if (this.lat && this.lng) {
                this.marker = L.marker([this.lat, this.lng]).addTo(this.map);
            }
            this.map.on('click', (e) => {
                this.lat = e.latlng.lat;
                this.lng = e.latlng.lng;
                if (this.marker) this.map.removeLayer(this.marker);
                this.marker = L.marker([this.lat, this.lng]).addTo(this.map);
                document.getElementById('edit-lat').value = this.lat;
                document.getElementById('edit-lng').value = this.lng;
            });
            setTimeout(() => this.map.invalidateSize(), 150);
        }
    }"
    x-init="$watch('showEditModal', v => { if (v) $nextTick(() => initMap()) })"
>
    <label class="block text-gray-700 mb-2">
        Pickup Location
        <span class="text-gray-400 text-sm font-normal">(click map to change)</span>
    </label>

    <div class="border border-gray-200 rounded-lg overflow-hidden">
        <div id="edit-donation-map" style="height: 240px; z-index: 1;"></div>
        <div class="px-4 py-2.5 bg-gray-50 border-t border-gray-200 flex items-center justify-between gap-3">
            <p class="text-sm text-gray-500">
                <template x-if="lat && lng">
                    <span>📍 <span x-text="lat.toFixed(6)"></span>, <span x-text="lng.toFixed(6)"></span></span>
                </template>
                <template x-if="!lat || !lng">
                    <span>No location selected</span>
                </template>
            </p>
            <button
                type="button"
                x-show="lat && lng"
                @click="
                    lat = null; lng = null;
                    document.getElementById('edit-lat').value = '';
                    document.getElementById('edit-lng').value = '';
                    if (marker) { map.removeLayer(marker); marker = null; }
                "
                class="text-xs text-red-500 hover:text-red-700"
            >
                Clear
            </button>
        </div>
    </div>

    <input type="hidden" name="pickup_latitude"  id="edit-lat"
           value="{{ isset($donation) ? $donation->pickup_latitude : '' }}"
           :value="lat ?? ''">
    <input type="hidden" name="pickup_longitude" id="edit-lng"
           value="{{ isset($donation) ? $donation->pickup_longitude : '' }}"
           :value="lng ?? ''">
</div>

            <div class="flex gap-3">

                <button
                    type="submit"
                    class="flex-1 bg-[#2E7D32] text-white py-3 rounded-lg hover:bg-[#25662a]"
                >
                    Save Changes
                </button>

                <button
                    type="button"
                    @click="showEditModal = false"
                    class="px-6 py-3 border border-gray-300 rounded-lg"
                >
                    Cancel
                </button>

            </div>

        </form>

    </div>
</div>

    <!-- Add Donation Modal -->
    @include('donations.modals.add-donation')
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endpush

@endsection
