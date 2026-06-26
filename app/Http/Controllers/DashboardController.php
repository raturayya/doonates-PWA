<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\RequestDonation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Scope semua query ke organisasi yang sedang login
        $orgName = Auth::user()->organization_name ?? Auth::user()->name;

        // 1. Setup Waktu (Bulan Ini & Bulan Lalu)
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear  = $now->year;

        $lastMonthDate = $now->copy()->subMonth();
        $lastMonth     = $lastMonthDate->month;
        $lastYear      = $lastMonthDate->year;

        // ==========================================
        // TOTAL DONATIONS (milik organisasi ini)
        // ==========================================
        $totalDonations = Donation::where('organization_name', $orgName)->count();

        $currentTotal = Donation::where('organization_name', $orgName)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)->count();
        $lastTotal = Donation::where('organization_name', $orgName)
            ->whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $lastYear)->count();

        $totalGrowth = $this->calculateGrowth($currentTotal, $lastTotal);

        // ==========================================
        // ACTIVE / PENDING REQUESTS (untuk donasi organisasi ini)
        // ==========================================
        $pendingRequests = RequestDonation::where('status', 'Pending')
            ->whereHas('donation', fn($q) => $q->where('organization_name', $orgName))
            ->count();

        $currentPending = RequestDonation::where('status', 'Pending')
            ->whereHas('donation', fn($q) => $q->where('organization_name', $orgName))
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)->count();
        $lastPending = RequestDonation::where('status', 'Pending')
            ->whereHas('donation', fn($q) => $q->where('organization_name', $orgName))
            ->whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $lastYear)->count();

        $pendingGrowth = $this->calculateGrowth($currentPending, $lastPending);

        // ==========================================
        // COMPLETED DONATIONS
        // ==========================================
        $completedDonations = Donation::where('organization_name', $orgName)
            ->where('status', 'Completed')->count();

        $currentCompleted = Donation::where('organization_name', $orgName)
            ->where('status', 'Completed')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)->count();
        $lastCompleted = Donation::where('organization_name', $orgName)
            ->where('status', 'Completed')
            ->whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $lastYear)->count();

        $completedGrowth = $this->calculateGrowth($currentCompleted, $lastCompleted);

        // ==========================================
        // AVAILABLE DONATIONS
        // ==========================================
        $availableDonations = Donation::where('organization_name', $orgName)
            ->where('status', 'Available')->count();

        $currentAvailable = Donation::where('organization_name', $orgName)
            ->where('status', 'Available')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)->count();
        $lastAvailable = Donation::where('organization_name', $orgName)
            ->where('status', 'Available')
            ->whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $lastYear)->count();

        $availableGrowth = $this->calculateGrowth($currentAvailable, $lastAvailable);

        // ==========================================
        // STATISTICS: Donations by Category
        // ==========================================
        $donationsByCategory = Donation::where('organization_name', $orgName)
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category');

        $categoryLabels = $donationsByCategory->keys();
        $categoryData   = $donationsByCategory->values();

        // ==========================================
        // STATISTICS: Donation Trends (Last 6 Months)
        // ==========================================
        $trendLabels = collect();
        $trendData   = collect();

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $trendLabels->push($month->format('M Y'));

            $count = Donation::where('organization_name', $orgName)
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();

            $trendData->push($count);
        }

        // ==========================================
        // RECENT DONATIONS TABLE
        // ==========================================
        $latestDonations = Donation::with('unit')
            ->where('organization_name', $orgName)
            ->orderByDesc('id')
            ->take(5)
            ->get();

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
     * Hitung persentase pertumbuhan bulanan.
     * Jika bulan lalu = 0 dan bulan ini ada data → anggap naik 100%.
     */
    private function calculateGrowth($current, $last): float|int
    {
        if ($last == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $last) / $last) * 100, 1);
    }
}