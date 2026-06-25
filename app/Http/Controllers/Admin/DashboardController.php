<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_organizations' => User::where('role', 'organization')->count(),
            'approved'            => User::where('role', 'organization')->where('status', 'approved')->count(),
            'pending'             => User::where('role', 'organization')->where('status', 'pending')->count(),
            'rejected'            => User::where('role', 'organization')->where('status', 'rejected')->count(),
        ];

        $pendingOrganizations = User::where('role', 'organization')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingOrganizations'));
    }
}