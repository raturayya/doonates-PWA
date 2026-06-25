<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\RequestDonation;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $query = Donation::with('unit')->where('status', 'Available');

        if ($request->search) {
            $query->where('food_name', 'like', '%' . $request->search . '%');
        }

        $donations = $query->latest()->paginate(10);

        return view('user.donations.index', compact('donations'));
    }

    public function show(Donation $donation)
    {
        $donation->load('unit', 'requests');
        return view('user.donations.show', compact('donation'));
    }

    public function request(Request $request, Donation $donation)
    {
        $request->validate([
            'requested_quantity' => 'required|integer|min:1',
            'pickup_time'        => 'required|date',
            'message'            => 'nullable|string',
        ]);

        $remainingStock = $donation->remaining_stock ?? $donation->remaining_quantity ?? $donation->quantity;

        if ($request->requested_quantity > $remainingStock) {
            return back()->with('error', "Requested quantity ({$request->requested_quantity}) exceeds remaining stock ({$remainingStock}).");
        }

        $user = Auth::user();

        RequestDonation::create([
            'donation_id'        => $donation->id,
            'organization_name'  => $user->organization_name ?? $user->name,
            'organization_type'  => $user->organization_type ?? 'Individual',
            'requested_quantity' => $request->requested_quantity,
            'pickup_time'        => $request->pickup_time,
            'message'            => $request->message ?? '',
            'status'             => 'Pending',
            'user_id'            => $user->id,
        ]);

        return redirect()->route('user.requests.index')->with('success', 'Request submitted successfully!');
    }
}
