<?php

namespace App\Http\Controllers;

use App\Models\RequestDonation;
use App\Models\Donation;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index()
    {
        $orgName = Auth::user()->organization_name ?? Auth::user()->name;

        $requests = RequestDonation::with(['donation.unit', 'user'])
            ->whereHas('donation', function ($q) use ($orgName) {
                $q->where('organization_name', $orgName);
            })
            ->latest()
            ->get();

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

        // Push notification to the user who made the request
        if ($requestDonation->user) {
            $url = route('user.requests.show', $requestDonation->id);
            app(PushNotificationService::class)->sendToUser(
                $requestDonation->user,
                '✅ Request Approved!',
                "Your request for \"{$donation->food_name}\" has been approved. Tap to view details.",
                $url
            );
        }

        return back()->with('success', 'Request approved successfully');
    }

    public function reject(RequestDonation $requestDonation)
    {
        $requestDonation->update(['status' => 'Rejected']);
        return back()->with('success', 'Request rejected');
    }

    public function setLocation(Request $request, RequestDonation $requestDonation)
    {
        $request->validate([
            'pickup_latitude'  => 'required|numeric|between:-90,90',
            'pickup_longitude' => 'required|numeric|between:-180,180',
        ]);

        if ($requestDonation->status !== 'Approved') {
            return back()->with('error', 'Can only set location for approved requests');
        }

        $requestDonation->update([
            'pickup_latitude'  => $request->pickup_latitude,
            'pickup_longitude' => $request->pickup_longitude,
        ]);

        return back()->with('success', 'Pickup location saved');
    }

    public function markPickedUp(RequestDonation $requestDonation)
    {
        if ($requestDonation->status !== 'Approved') {
            return back()->with('error', 'Request is not in Approved state');
        }

        $requestDonation->update(['status' => 'Finished']);

        return back()->with('success', 'Marked as picked up');
    }
}