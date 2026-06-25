@extends('layouts.app')

@section('title', 'Monitoring')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Monitoring</h1>
        <p class="text-gray-500 mt-1">Pantau seluruh aktivitas organisasi</p>
    </div>

    {{-- Stats Cards --}}
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem;">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-[#2E7D32]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_organizations'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Organisasi</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['approved'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Aktif</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Pending</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['rejected'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Rejected</p>
        </div>
    </div>

    {{-- Tabel Semua Organisasi --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <h2 class="font-semibold text-gray-900">Semua Organisasi</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Organisasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telepon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bergabung</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($organizations as $org)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-400">{{ $organizations->firstItem() + $loop->index }}</td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900">{{ $org->organization_name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ $org->address }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $org->organization_type ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $org->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $org->phone ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($org->status === 'approved')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    Approved
                                </span>
                            @elseif($org->status === 'pending')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                    Pending
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    Rejected
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $org->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-400">Belum ada organisasi terdaftar</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($organizations->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $organizations->links() }}
            </div>
        @endif
    </div>

</div>
@endsection