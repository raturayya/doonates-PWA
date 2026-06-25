<div
    x-show="showModal"
    x-cloak
    class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50"
    @click.self="showModal = false"
>
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
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

        <form method="POST" action="{{ route('donations.store') }}" class="p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-gray-700 mb-2">Food Name</label>
                    <input
                        type="text"
                        name="food_name"
                        required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent"
                        placeholder="e.g., Fresh Vegetables"
                    />
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Category</label>
                    <select
                        name="category"
                        required
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-gray-700 mb-2">Quantity</label>
                    <input
                        type="number"
                        min="1"
                        name="quantity"
                        required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent"
                    >
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Unit</label>
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

            <div>
                <label class="block text-gray-700 mb-2">Expiry Date</label>
                <input
                    type="date"
                    name="expiry_date"
                    required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent"
                />
            </div>

            <div>
                <label class="block text-gray-700 mb-2">Pickup Time</label>
                <input
                    type="datetime-local"
                    name="pickup_time"
                    required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent"
                />
            </div>

            <div>
                <label class="block text-gray-700 mb-2">Description</label>
                <textarea
                    name="description"
                    required
                    rows="3"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent resize-none"
                    placeholder="Add any additional details about the donation..."
                ></textarea>
            </div>

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