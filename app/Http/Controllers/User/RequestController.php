<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RequestDonation;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $requests = RequestDonation::with('donation.unit')
            ->where('organization_name', $user->organization_name ?? $user->name)
            ->latest()->get();

        return view('user.requests.index', compact('requests'));
    }
}
