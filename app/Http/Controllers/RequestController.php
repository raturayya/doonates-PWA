<?php

namespace App\Http\Controllers;

use App\Models\RequestDonation;
use App\Models\Donation;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function index()
    {
        $requests = RequestDonation::with([
            'donation.unit',
            'user'
        ])->latest()->get();
        return view('requests.index', compact('requests'));
    }

    public function approve(RequestDonation $requestDonation)
    {
        $donation = $requestDonation->donation;

        if (!$donation) {
            return back()->with('error', 'Donation not found');
        }

        if ($requestDonation->status === 'Approved') {
            return back()->with('error', 'Request already approved');
        }

        $takenQty = $requestDonation->requested_quantity ?? $requestDonation->portions ?? 0;
        $currentRemaining = $donation->remaining_stock ?? $donation->quantity;

        if ($takenQty > $currentRemaining) {
            return back()->with('error', 'Requested quantity exceeds remaining stock');
        }

        $requestDonation->update(['status' => 'Approved']);

        $newRemaining   = $currentRemaining - $takenQty;
        $newTotalTaken  = ($donation->total_taken ?? 0) + $takenQty;

        $donation->update([
            'remaining_stock'    => $newRemaining,
            'total_taken'        => $newTotalTaken,
            'status'             => $newRemaining <= 0 ? 'Completed' : 'Available',
        ]);

        return back()->with('success', 'Request approved successfully');
    }

    public function reject(RequestDonation $requestDonation)
    {
        $requestDonation->update(['status' => 'Rejected']);
        return back()->with('success', 'Request rejected');
    }
}
