<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RequestDonation;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $requests = RequestDonation::with('donation.unit')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        return view('user.requests.index', compact('requests'));
    }

    public function show(RequestDonation $requestDonation)
    {
        if ($requestDonation->user_id !== Auth::id()) {
            abort(403);
        }

        $requestDonation->load('donation.unit');

        return view('user.requests.show', compact('requestDonation'));
    }

    public function markPickedUp(RequestDonation $requestDonation)
    {
        if ($requestDonation->user_id !== Auth::id()) {
            abort(403);
        }

        if ($requestDonation->status !== 'Approved') {
            return back()->with('error', 'Request is not in Approved state');
        }

        $requestDonation->update(['status' => 'Finished']);

        return back()->with('success', 'Marked as picked up! Thank you.');
    }
}
