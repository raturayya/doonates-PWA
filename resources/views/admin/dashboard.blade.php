@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="text-gray-500 mt-1">Selamat datang, {{ auth()->user()->name }}</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Total Organisasi --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#2E7D32]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium text-green-600 bg-green-50 px-2.5 py-1 rounded-full">Total</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_organizations'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Organisasi</p>
        </div>

        {{-- Pending --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium text-yellow-600 bg-yellow-50 px-2.5 py-1 rounded-full">Menunggu</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Menunggu Verifikasi</p>
        </div>

        {{-- Approved --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">Aktif</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['approved'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Organisasi Aktif</p>
        </div>

    </div>

    {{-- Tabel Pending Terbaru --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h2 class="font-semibold text-gray-900">Organisasi Menunggu Verifikasi</h2>
            <a href="{{ route('admin.verification.index') }}"
                class="text-sm text-[#2E7D32] hover:underline">
                Lihat semua →
            </a>
        </div>

        @if($pendingOrganizations->isEmpty())
            <div class="p-8 text-center text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p>Tidak ada organisasi yang menunggu verifikasi</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organisasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daftar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($pendingOrganizations as $org)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $org->organization_name }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $org->organization_type }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $org->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $org->created_at->diffForHumans() }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('admin.verification.approve', $org) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="text-xs bg-green-100 text-green-700 px-3 py-1.5 rounded-lg hover:bg-green-200 transition-colors">
                                            Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.verification.reject', $org) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="text-xs bg-red-100 text-red-700 px-3 py-1.5 rounded-lg hover:bg-red-200 transition-colors">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection