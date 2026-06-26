@extends('layouts.app')

@section('title', 'Requests Management')

@section('content')
<div x-data="{
    selectedRequest: null,
    requests: @js($requests)
}">
    <div class="mb-6">
        <h1 class="text-gray-900 mb-2">Requests Management</h1>
        <p class="text-gray-600">Review and manage incoming donation requests</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-gray-900">Incoming Requests</h2>
                <div class="flex gap-2">
                    <button class="px-4 py-2 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 text-sm">
                        All ({{ $requests->count() ?? 0 }})
                    </button>
                    <button class="px-4 py-2 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 text-sm">
                        Pending ({{ $requests->where('status', 'Pending')->count() ?? 0 }})
                    </button>
                </div>
            </div>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse($requests ?? [] as $req)
            <div class="p-6 hover:bg-gray-50">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex items-start gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    
                                    {{-- ðŸ”¥ FIX: ambil dari relasi donation --}}
                                    <h3 class="text-gray-900">
                                        {{ $req->donation->food_name ?? '-' }}
                                    </h3>

                                    <x-badge :status="$req->status" />
                                </div>

                                <div class="flex items-center gap-6 text-sm text-gray-600 mb-3">
                                    <div>
                                        <span class="text-gray-500">Requested by:</span>
                                        <span class="text-gray-900">{{ $req->organization_name }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Type:</span> {{ $req->organization_type }}
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Requested:</span> {{ $req->requested_quantity ?? $req->portions }} {{ $req->donation->unit->name ?? '' }}<br><span class="text-gray-500">Remaining:</span> <span class="font-medium text-[#2E7D32]">{{ $req->donation->remaining_stock ?? $req->donation->remaining_quantity ?? '-' }} {{ $req->donation->unit->name ?? '' }}</span>
                                    </div>
                                </div>

                                <div class="text-sm text-gray-600 mb-3">
                                    <span class="text-gray-500">Pickup:</span> 
                                    {{ \Carbon\Carbon::parse($req->pickup_time)->format('d M Y H:i') }}
                                </div>

                                <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">
                                    {{ $req->message }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- ðŸ”¥ ACTION BUTTON --}}
                    @if($req->status === 'Pending')
                    <div class="flex gap-2 ml-4">
                        
                        <button
                            @click="selectedRequest = {{ $req->id }}"
                            class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            View
                        </button>

                        {{-- âœ… FIX PATCH METHOD --}}
                        <form method="POST" action="{{ route('requests.approve', $req->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-[#2E7D32] text-white rounded-lg hover:bg-[#25662a] transition-colors">
                                Approve
                            </button>
                        </form>

                        <form method="POST" action="{{ route('requests.reject', $req->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                                Reject
                            </button>
                        </form>
                    </div>
                    @endif
                </div>

                <p class="text-xs text-gray-500">
                    Requested on {{ $req->created_at->format('d M Y H:i') }}
                </p>
            </div>
            @empty
            <div class="text-center py-12">
                <p class="text-gray-500">No requests at the moment</p>
            </div>
            @endforelse
        </div>
    </div>

    @include('requests.modals.request-detail')
</div>
@endsection