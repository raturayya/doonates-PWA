@extends('layouts.app')

@section('title', 'Verification')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Organization Verification</h1>
        <p class="text-gray-500 mt-1">Kelola pendaftaran organisasi yang masuk</p>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tab: Pending --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <h2 class="font-semibold text-gray-900">Pending</h2>
                @if($pending->count() > 0)
                    <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                        {{ $pending->count() }}
                    </span>
                @endif
            </div>
        </div>

        @if($pending->isEmpty())
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Organisasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telepon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Daftar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($pending as $org)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $org->organization_name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $org->address }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $org->organization_type }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $org->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $org->phone }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $org->created_at->diffForHumans() }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('admin.verification.approve', $org) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="text-xs bg-green-100 text-green-700 px-3 py-1.5 rounded-lg hover:bg-green-200 transition-colors font-medium">
                                            ✓ Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.verification.reject', $org) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="text-xs bg-red-100 text-red-700 px-3 py-1.5 rounded-lg hover:bg-red-200 transition-colors font-medium">
                                            ✗ Reject
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

    {{-- Tab: Approved --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="p-6 border-b border-gray-100 flex items-center gap-3">
            <h2 class="font-semibold text-gray-900">Approved</h2>
            <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                {{ $approved->count() }}
            </span>
        </div>

        @if($approved->isEmpty())
            <div class="p-6 text-center text-gray-400 text-sm">Belum ada organisasi yang diapprove</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Organisasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($approved as $org)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $org->organization_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $org->organization_type }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $org->email }}</td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('admin.verification.reject', $org) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="text-xs bg-red-100 text-red-700 px-3 py-1.5 rounded-lg hover:bg-red-200 transition-colors font-medium">
                                        Revoke
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Tab: Rejected --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="p-6 border-b border-gray-100 flex items-center gap-3">
            <h2 class="font-semibold text-gray-900">Rejected</h2>
            <span class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                {{ $rejected->count() }}
            </span>
        </div>

        @if($rejected->isEmpty())
            <div class="p-6 text-center text-gray-400 text-sm">Belum ada organisasi yang direject</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Organisasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($rejected as $org)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $org->organization_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $org->organization_type }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $org->email }}</td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('admin.verification.approve', $org) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="text-xs bg-green-100 text-green-700 px-3 py-1.5 rounded-lg hover:bg-green-200 transition-colors font-medium">
                                        Re-approve
                                    </button>
                                </form>
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