<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\RequestDonation;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Setup Waktu (Bulan Ini & Bulan Lalu)
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        $lastMonthDate = $now->copy()->subMonth();
        $lastMonth = $lastMonthDate->month;
        $lastYear = $lastMonthDate->year;

        // ==========================================
        // TOTAL DONATIONS
        // ==========================================
        $totalDonations = Donation::count(); // Angka utama (All-time)
        
        $currentTotal = Donation::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)->count();
        $lastTotal = Donation::whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $lastYear)->count();
            
        $totalGrowth = $this->calculateGrowth($currentTotal, $lastTotal);

        // ==========================================
        // ACTIVE / PENDING REQUESTS
        // ==========================================
        $pendingRequests = RequestDonation::where('status', 'Pending')->count();
        
        $currentPending = RequestDonation::where('status', 'Pending')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)->count();
        $lastPending = RequestDonation::where('status', 'Pending')
            ->whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $lastYear)->count();
            
        $pendingGrowth = $this->calculateGrowth($currentPending, $lastPending);

        // ==========================================
        // COMPLETED DONATIONS
        // ==========================================
        $completedDonations = Donation::where('status', 'Completed')->count();
        
        $currentCompleted = Donation::where('status', 'Completed')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)->count();
        $lastCompleted = Donation::where('status', 'Completed')
            ->whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $lastYear)->count();
            
        $completedGrowth = $this->calculateGrowth($currentCompleted, $lastCompleted);

        // ==========================================
        // AVAILABLE DONATIONS
        // ==========================================
        $availableDonations = Donation::where('status', 'Available')->count();
        
        $currentAvailable = Donation::where('status', 'Available')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)->count();
        $lastAvailable = Donation::where('status', 'Available')
            ->whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $lastYear)->count();
            
        $availableGrowth = $this->calculateGrowth($currentAvailable, $lastAvailable);

        // ==========================================
        // STATISTICS: Donations by Category
        // ==========================================
        $donationsByCategory = Donation::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category');

        $categoryLabels = $donationsByCategory->keys();
        $categoryData = $donationsByCategory->values();

        // ==========================================
        // STATISTICS: Donation Trends (Last 6 Months)
        // ==========================================
        $trendLabels = collect();
        $trendData = collect();
        
        // Looping mundur dari 5 bulan lalu sampai bulan ini
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $trendLabels->push($month->format('M Y')); 
            
            $count = Donation::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
                
            $trendData->push($count);
        }

        // ==========================================
        // RECENT DONATIONS TABLE
        // ==========================================
        // Menggunakan with('unit') agar relasi unit langsung dimuat (mencegah N+1 Query)
        $latestDonations = Donation::with('unit')->orderByDesc('id')->take(5)->get();

        return view('dashboard', compact(
            'totalDonations', 'totalGrowth',
            'pendingRequests', 'pendingGrowth',
            'completedDonations', 'completedGrowth',
            'availableDonations', 'availableGrowth',
            'latestDonations',
            'categoryLabels', 'categoryData', 
            'trendLabels', 'trendData'
        ));
    }

    /**
     * Fungsi helper untuk menghitung persentase pertumbuhan bulanan
     */
    private function calculateGrowth($current, $last)
    {
        // Jika bulan lalu tidak ada data sama sekali
        if ($last == 0) {
            return $current > 0 ? 100 : 0; // Jika bulan ini ada data, anggap naik 100%
        }
        
        $growth = (($current - $last) / $last) * 100;
        return round($growth, 1); // Bulatkan 1 angka di belakang koma (misal: 12.5)
    }
}