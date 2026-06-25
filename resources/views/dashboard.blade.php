@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div>

    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-2">Dashboard Overview</h1>
        <p class="text-gray-600">Track your food donation activities and impact</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="bg-white p-6 rounded-xl shadow-sm border hover:shadow-md transition">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
                    📦
                </div>
                <span class="text-sm font-medium {{ ($totalGrowth ?? 0) > 0 ? 'text-green-600' : (($totalGrowth ?? 0) < 0 ? 'text-red-600' : 'text-gray-500') }}">
                    {{ ($totalGrowth ?? 0) > 0 ? '+' : '' }}{{ $totalGrowth ?? 0 }}%
                </span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $totalDonations ?? 0 }}</p>
            <p class="text-sm text-gray-500">Total Donations</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border hover:shadow-md transition">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-lg flex items-center justify-center">
                    ⏳
                </div>
                <span class="text-sm font-medium {{ ($pendingGrowth ?? 0) > 0 ? 'text-green-600' : (($pendingGrowth ?? 0) < 0 ? 'text-red-600' : 'text-gray-500') }}">
                    {{ ($pendingGrowth ?? 0) > 0 ? '+' : '' }}{{ $pendingGrowth ?? 0 }}%
                </span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $pendingRequests ?? 0 }}</p>
            <p class="text-sm text-gray-500">Active Requests</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border hover:shadow-md transition">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                    ✅
                </div>
                <span class="text-sm font-medium {{ ($completedGrowth ?? 0) > 0 ? 'text-green-600' : (($completedGrowth ?? 0) < 0 ? 'text-red-600' : 'text-gray-500') }}">
                    {{ ($completedGrowth ?? 0) > 0 ? '+' : '' }}{{ $completedGrowth ?? 0 }}%
                </span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $completedDonations ?? 0 }}</p>
            <p class="text-sm text-gray-500">Completed Donations</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border hover:shadow-md transition">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center">
                    🏢
                </div>
                <span class="text-sm font-medium {{ ($availableGrowth ?? 0) > 0 ? 'text-green-600' : (($availableGrowth ?? 0) < 0 ? 'text-red-600' : 'text-gray-500') }}">
                    {{ ($availableGrowth ?? 0) > 0 ? '+' : '' }}{{ $availableGrowth ?? 0 }}%
                </span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $availableDonations ?? 0 }}</p>
            <p class="text-sm text-gray-500">Available Donations</p>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Donation Trends (Last 6 Months)</h2>
            <div class="relative h-64 w-full">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Donations by Category</h2>
            <div class="relative h-64 w-full flex justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-900">Recent Donations</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="p-4 text-left">Food Name</th>
                        <th class="p-4 text-left">Category</th>
                        <th class="p-4 text-left">Quantity</th>
                        <th class="p-4 text-left">Pickup Time</th>
                        <th class="p-4 text-left">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($latestDonations ?? [] as $donation)
                        <tr class="hover:bg-gray-50 transition">

                            <td class="p-4 font-medium text-gray-900">
                                {{ $donation->food_name }}
                            </td>

                            <td class="p-4 text-gray-600">
                                {{ $donation->category }}
                            </td>

                            <td class="p-4 text-gray-600">
                                {{ $donation->original_stock ?? $donation->quantity }} {{ $donation->unit->name ?? '' }} / Sisa: {{ $donation->remaining_stock ?? $donation->remaining_quantity ?? $donation->quantity }} {{ $donation->unit->name ?? '' }}
                            </td>

                            <td class="p-4 text-gray-600">
                                {{ $donation->pickup_time 
                                    ? \Carbon\Carbon::parse($donation->pickup_time)->format('d M Y H:i') 
                                    : '-' }}
                            </td>

                            <td class="p-4">
                                <x-badge :status="$donation->status" />
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center p-6 text-gray-500">
                                No data available
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // Data dari Controller ke Javascript, gunakan array kosong sebagai fallback jika variabel tidak ada
        const trendLabels = @json($trendLabels ?? []);
        const trendData = @json($trendData ?? []);
        const categoryLabels = @json($categoryLabels ?? []);
        const categoryData = @json($categoryData ?? []);

        // 1. Setup Trend Line Chart
        const ctxTrend = document.getElementById('trendChart');
        if (ctxTrend && trendLabels.length > 0) {
            new Chart(ctxTrend.getContext('2d'), {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [{
                        label: 'Total Donations',
                        data: trendData,
                        borderColor: '#2E7D32',
                        backgroundColor: 'rgba(46, 125, 50, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            });
        }

        // 2. Setup Category Doughnut Chart
        const ctxCategory = document.getElementById('categoryChart');
        if (ctxCategory && categoryLabels.length > 0) {
            new Chart(ctxCategory.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: categoryLabels,
                    datasets: [{
                        data: categoryData,
                        backgroundColor: [
                            '#4CAF50', '#FF9800', '#2196F3', '#9C27B0', '#F44336', '#FFEB3B'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right' }
                    }
                }
            });
        }

    });
</script>
@endsection