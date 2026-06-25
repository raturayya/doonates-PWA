<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\RequestDonation;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $availableDonations = Donation::where('status', 'Available')->count();

        $myRequests = RequestDonation::where('organization_name', $user->organization_name)->count();

        $approvedRequests = RequestDonation::where('organization_name', $user->organization_name)
            ->where('status', 'Approved')->count();

        $recentDonations = Donation::with('unit')
            ->where('status', 'Available')
            ->latest()->take(6)->get();

        return view('user.dashboard', compact(
            'availableDonations', 'myRequests', 'approvedRequests', 'recentDonations'
        ));
    }
}
