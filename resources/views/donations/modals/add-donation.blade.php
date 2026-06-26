<div
    x-show="showModal"
    x-cloak
    class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50"
    @click.self="showModal = false"
>
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">

        {{-- Header --}}
        <div class="p-6 border-b border-gray-200 flex items-center justify-between sticky top-0 bg-white z-10">
            <h2 class="text-gray-900 font-semibold text-lg">Add New Donation</h2>
            <button
                @click="showModal = false"
                class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form
            method="POST"
            action="{{ route('donations.store') }}"
            class="p-6 space-y-5"
            x-data="{
                foodName: '',
                category: '',
                aiLoading: false,
                aiError: '',
                aiGenerated: false,
                description: '',
                allergens: [],
                storageRecommendation: '',
                shelfLife: '',
                handlingRecommendation: '',

                async generateWithAI() {
                    if (!this.foodName.trim()) {
                        this.aiError = 'Please enter a food name first.';
                        return;
                    }

                    this.aiLoading = true;
                    this.aiError = '';
                    this.aiGenerated = false;

                    try {
                        const res = await fetch('{{ route('donations.ai-generate') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                food_name: this.foodName,
                                category: this.category || null,
                            }),
                        });

                        const json = await res.json();

                        if (!res.ok || !json.success) {
                            this.aiError = json.message || 'Failed to generate. Please try again.';
                            return;
                        }

                        const d = json.data;
                        this.description             = d.description             || '';
                        this.allergens               = d.allergens               || [];
                        this.storageRecommendation   = d.storage_recommendation  || '';
                        this.shelfLife               = d.shelf_life              || '';
                        this.handlingRecommendation  = d.handling_recommendation || '';
                        this.aiGenerated = true;

                    } catch (err) {
                        this.aiError = 'Network error. Please check your connection and try again.';
                    } finally {
                        this.aiLoading = false;
                    }
                },

                toggleAllergen(label) {
                    const idx = this.allergens.indexOf(label);
                    if (idx === -1) {
                        this.allergens.push(label);
                    } else {
                        this.allergens.splice(idx, 1);
                    }
                },

                buildDescription() {
                    let parts = [this.description.trim()];
                    if (this.allergens.length) {
                        parts.push('Allergens: ' + this.allergens.join(', ') + '.');
                    }
                    if (this.storageRecommendation.trim()) {
                        parts.push('Storage: ' + this.storageRecommendation.trim());
                    }
                    if (this.shelfLife.trim()) {
                        parts.push('Shelf life: ' + this.shelfLife.trim());
                    }
                    if (this.handlingRecommendation.trim()) {
                        parts.push('Handling: ' + this.handlingRecommendation.trim());
                    }
                    return parts.filter(Boolean).join('\n\n');
                }
            }"
        >
            @csrf

            {{-- Food Name + Category --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-gray-700 mb-2">Food Name <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        name="food_name"
                        required
                        x-model="foodName"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent"
                        placeholder="e.g., Nasi Goreng Ayam"
                    />
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                    <select
                        name="category"
                        required
                        x-model="category"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent"
                    >
                        <option value="">Select category</option>
                        <option value="Vegetables">Vegetables</option>
                        <option value="Fruits">Fruits</option>
                        <option value="Prepared Meals">Prepared Meals</option>
                        <option value="Baked Goods">Baked Goods</option>
                        <option value="Dairy">Dairy Products</option>
                        <option value="Packaged">Packaged Foods</option>
                    </select>
                </div>
            </div>

            {{-- Generate with AI Button --}}
            <div>
                <button
                    type="button"
                    @click="generateWithAI()"
                    :disabled="aiLoading || !foodName.trim()"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg border-2 border-dashed transition-all duration-200
                           text-[#2E7D32] border-[#2E7D32] hover:bg-[#2E7D32] hover:text-white
                           disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-transparent disabled:hover:text-[#2E7D32]"
                >
                    {{-- Spinner --}}
                    <svg x-show="aiLoading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    {{-- Sparkle icon --}}
                    <svg x-show="!aiLoading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 3l1.5 4.5L11 9l-4.5 1.5L5 15l-1.5-4.5L-1 9l4.5-1.5L5 3zM19 13l1 3 3 1-3 1-1 3-1-3-3-1 3-1 1-3z">
                        </path>
                    </svg>
                    <span x-text="aiLoading ? 'Generating...' : (aiGenerated ? '✓ Regenerate with AI' : 'Generate with AI')"></span>
                </button>

                {{-- Error message --}}
                <p x-show="aiError" x-text="aiError" class="mt-2 text-sm text-red-600 text-center"></p>
            </div>

            {{-- AI Generated Fields — visible after generation --}}
            <div x-show="aiGenerated" x-transition class="space-y-4 rounded-xl border border-[#2E7D32]/30 bg-green-50/50 p-4">
                <p class="text-xs font-medium text-[#2E7D32] uppercase tracking-wide flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    AI Generated — you can edit all fields below
                </p>

                {{-- Description --}}
                <div>
                    <label class="block text-gray-700 text-sm mb-1.5">Description</label>
                    <textarea
                        x-model="description"
                        rows="3"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent resize-none bg-white"
                        placeholder="Donation description..."
                    ></textarea>
                </div>

                {{-- Allergens --}}
                <div>
                    <label class="block text-gray-700 text-sm mb-1.5">Possible Allergens</label>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="tag in ['Eggs','Milk','Soy','Peanuts','Tree Nuts','Chicken','Seafood','Gluten','Sesame','Shellfish']" :key="tag">
                            <button
                                type="button"
                                @click="toggleAllergen(tag)"
                                :class="allergens.includes(tag)
                                    ? 'bg-[#2E7D32] text-white border-[#2E7D32]'
                                    : 'bg-white text-gray-600 border-gray-200 hover:border-[#2E7D32] hover:text-[#2E7D32]'"
                                class="px-3 py-1 rounded-full border text-xs font-medium transition-colors"
                                x-text="tag"
                            ></button>
                        </template>
                    </div>
                    <p class="mt-1 text-xs text-gray-400">Click to toggle. Green = present in this food.</p>
                </div>

                {{-- Storage Recommendation --}}
                <div>
                    <label class="block text-gray-700 text-sm mb-1.5">Storage Recommendation</label>
                    <textarea
                        x-model="storageRecommendation"
                        rows="2"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent resize-none bg-white"
                        placeholder="How to store this food..."
                    ></textarea>
                </div>

                {{-- Shelf Life --}}
                <div>
                    <label class="block text-gray-700 text-sm mb-1.5">Estimated Safe Shelf Life at Room Temperature</label>
                    <input
                        type="text"
                        x-model="shelfLife"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent bg-white"
                        placeholder="e.g., 2–4 hours"
                    />
                </div>

                {{-- Handling Recommendation --}}
                <div>
                    <label class="block text-gray-700 text-sm mb-1.5">Food Handling Recommendation</label>
                    <textarea
                        x-model="handlingRecommendation"
                        rows="2"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent resize-none bg-white"
                        placeholder="Safe handling and serving advice..."
                    ></textarea>
                </div>
            </div>

            {{-- Description field (plain) — shown when AI NOT used --}}
            <div x-show="!aiGenerated">
                <label class="block text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
                <textarea
                    rows="3"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent resize-none"
                    placeholder="Add any additional details about the donation..."
                    x-model="description"
                ></textarea>
            </div>

            {{-- Hidden field that submits the assembled description --}}
            <input type="hidden" name="description" :value="buildDescription() || description">

            {{-- Quantity + Unit --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-gray-700 mb-2">Quantity <span class="text-red-500">*</span></label>
                    <input
                        type="number"
                        min="1"
                        name="quantity"
                        required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent"
                    >
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Unit <span class="text-red-500">*</span></label>
                    <select
                        name="unit_id"
                        required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent"
                    >
                        <option value="">Select Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Expiry Date --}}
            <div>
                <label class="block text-gray-700 mb-2">Expiry Date <span class="text-red-500">*</span></label>
                <input
                    type="date"
                    name="expiry_date"
                    required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent"
                />
            </div>

            {{-- Pickup Time --}}
            <div>
                <label class="block text-gray-700 mb-2">Pickup Time <span class="text-red-500">*</span></label>
                <input
                    type="datetime-local"
                    name="pickup_time"
                    required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent"
                />
            </div>

            {{-- Pickup Location Map --}}
            <div
                x-data="{
                    lat: null,
                    lng: null,
                    map: null,
                    marker: null,
                    initMap() {
                        if (this.map) return;
                        this.map = L.map('add-donation-map').setView([-6.2088, 106.8456], 12);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap contributors'
                        }).addTo(this.map);
                        this.map.on('click', (e) => {
                            this.lat = e.latlng.lat;
                            this.lng = e.latlng.lng;
                            if (this.marker) this.map.removeLayer(this.marker);
                            this.marker = L.marker([this.lat, this.lng]).addTo(this.map);
                            document.getElementById('add-lat').value = this.lat;
                            document.getElementById('add-lng').value = this.lng;
                        });
                        setTimeout(() => this.map.invalidateSize(), 150);
                    }
                }"
                x-init="$watch('$el.closest('.fixed').style.display', v => { if (v !== 'none') $nextTick(() => initMap()) })"
                @donation-modal-opened.window="$nextTick(() => initMap())"
            >
                <label class="block text-gray-700 mb-2">
                    Pickup Location
                    <span class="text-gray-400 text-sm font-normal">(optional — click map to set)</span>
                </label>

                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div id="add-donation-map" style="height: 260px; z-index: 1;"></div>

                    <div class="px-4 py-2.5 bg-gray-50 border-t border-gray-200 flex items-center justify-between gap-3">
                        <p class="text-sm text-gray-500">
                            <template x-if="lat && lng">
                                <span>
                                    📍 <span x-text="lat.toFixed(6)"></span>, <span x-text="lng.toFixed(6)"></span>
                                </span>
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
                                document.getElementById('add-lat').value = '';
                                document.getElementById('add-lng').value = '';
                                if (marker) { map.removeLayer(marker); marker = null; }
                            "
                            class="text-xs text-red-500 hover:text-red-700"
                        >
                            Clear
                        </button>
                    </div>
                </div>

                <input type="hidden" name="pickup_latitude"  id="add-lat">
                <input type="hidden" name="pickup_longitude" id="add-lng">
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-4">
                <button
                    type="submit"
                    class="flex-1 bg-[#2E7D32] text-white py-3 rounded-lg hover:bg-[#25662a] transition-colors"
                >
                    Add Donation
                </button>
                <button
                    type="button"
                    @click="showModal = false"
                    class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                >
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
</style>