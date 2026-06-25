<div
    x-show="selectedRequest !== null"
    x-cloak
    class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50"
    @click.self="selectedRequest = null"
>
    <div 
        x-data="{ 
            get selected() {
                return requests.find(r => r.id === selectedRequest)
            } 
        }"
        class="bg-white rounded-xl shadow-xl max-w-2xl w-full"
    >
        <!-- HEADER -->
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-gray-900">Request Details</h2>
            <button
                @click="selectedRequest = null"
                class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100"
            >
                ✕
            </button>
        </div>

        <!-- CONTENT -->
        <div class="p-6 space-y-6" x-show="selected">
            
            <!-- FOOD -->
            <div class="flex items-start gap-3">
                <div class="p-2 bg-green-100 rounded-lg">🍱</div>
                <div class="flex-1">
                    <p class="text-sm text-gray-500 mb-1">Food Item</p>
                    <p class="text-gray-900 font-medium"
                       x-text="selected?.donation?.food_name ?? '-'">
                    </p>
                </div>
            </div>

            <!-- ORGANIZATION -->
            <div class="flex items-start gap-3">
                <div class="p-2 bg-blue-100 rounded-lg">🏢</div>
                <div class="flex-1">
                    <p class="text-sm text-gray-500 mb-1">Requested By</p>
                    <p class="text-gray-900 font-medium"
                       x-text="selected?.organization_name">
                    </p>
                    <p class="text-sm text-gray-600"
                       x-text="selected?.organization_type">
                    </p>
                </div>
            </div>

            <!-- GRID -->
            <div class="grid grid-cols-2 gap-4">

                <div class="flex items-start gap-3">
                    <div class="p-2 bg-purple-100 rounded-lg">📦</div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-500 mb-1">Quantity</p>
                        <p class="text-gray-900"
                           x-text="selected?.requested_quantity">
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="p-2 bg-yellow-100 rounded-lg">⏰</div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-500 mb-1">Pickup Time</p>
                        <p class="text-gray-900"
                           x-text="new Date(selected?.pickup_time).toLocaleString()">
                        </p>
                    </div>
                </div>

            </div>

            <!-- REQUEST DATE -->
            <div class="flex items-start gap-3">
                <div class="p-2 bg-gray-100 rounded-lg">📅</div>
                <div class="flex-1">
                    <p class="text-sm text-gray-500 mb-1">Request Date</p>
                    <p class="text-gray-900"
                       x-text="new Date(selected?.created_at).toLocaleString()">
                    </p>
                </div>
            </div>

            <!-- MESSAGE -->
            <div class="flex items-start gap-3">
                <div class="p-2 bg-orange-100 rounded-lg">💬</div>
                <div class="flex-1">
                    <p class="text-sm text-gray-500 mb-2">Message</p>
                    <p class="text-gray-900 bg-gray-50 p-4 rounded-lg"
                       x-text="selected?.message">
                    </p>
                </div>
            </div>
        </div>

        <!-- ACTION -->
        <div class="p-6 border-t border-gray-200 flex gap-3" x-show="selected?.status === 'Pending'">
            
            <!-- APPROVE -->
            <form :action="`/requests/${selected.id}/approve`" method="POST" class="flex-1">
                @csrf
                @method('PATCH')
                <button type="submit"
                    class="w-full bg-[#2E7D32] text-white py-3 rounded-lg hover:bg-[#25662a] transition-colors">
                    Approve Request
                </button>
            </form>

            <!-- REJECT -->
            <form :action="`/requests/${selected.id}/reject`" method="POST" class="flex-1">
                @csrf
                @method('PATCH')
                <button type="submit"
                    class="w-full border border-red-300 text-red-600 py-3 rounded-lg hover:bg-red-50 transition-colors">
                    Reject Request
                </button>
            </form>

        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
</style>