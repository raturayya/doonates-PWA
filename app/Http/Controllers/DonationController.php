<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $query = Donation::with('unit');

        if ($request->search) {
            $query->where('food_name', 'like', '%' . $request->search . '%');
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $donations = $query->latest()->paginate(10);
        $units = Unit::all();

        return view('donations.index', compact('donations', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'food_name'   => 'required|string',
            'category'    => 'required|string',
            'quantity'    => 'required|integer|min:1',
            'unit_id'     => 'required|exists:units,id',
            'expiry_date' => 'required|date',
            'pickup_time' => 'required|date',
            'description' => 'required|string',
        ]);

        $qty = $request->quantity;

        Donation::create([
            'food_name'         => $request->food_name,
            'category'          => $request->category,
            'quantity'          => $qty,
            'original_stock'    => $qty,
            'remaining_stock'   => $qty,
            'total_taken'       => 0,
            'unit_id'           => $request->unit_id,
            'expiry_date'       => $request->expiry_date,
            'pickup_time'       => $request->pickup_time,
            'description'       => $request->description,
            'status'            => 'Available',
            'organization_name' => Auth::user()->organization_name ?? Auth::user()->name,
        ]);

        return redirect()->route('donations.index')->with('success', 'Donation added!');
    }

    public function show(Donation $donation)
    {
        $donation->load('unit', 'requests');
        return view('donations.show', compact('donation'));
    }

    public function edit(Donation $donation)
    {
        $units = Unit::all();
        return view('donations.edit', compact('donation', 'units'));
    }

    public function update(Request $request, Donation $donation)
    {
        $request->validate([
            'food_name'   => 'required|string',
            'category'    => 'required|string',
            'quantity'    => 'required|integer|min:1',
            'unit_id'     => 'required|exists:units,id',
            'expiry_date' => 'required|date',
            'pickup_time' => 'required|date',
            'description' => 'required|string',
        ]);

        $donation->update($request->only([
            'food_name', 'category', 'quantity', 'unit_id',
            'expiry_date', 'pickup_time', 'description',
        ]));

        return redirect()->route('donations.index')->with('success', 'Donation updated!');
    }

    public function destroy(Donation $donation)
    {
        $donation->delete();
        return redirect()->route('donations.index')->with('success', 'Donation deleted!');
    }

    public function updateStatus(Request $request, Donation $donation)
    {
        $request->validate(['status' => 'required|in:Available,Requested,Approved,Completed']);
        $donation->update(['status' => $request->status]);
        return back()->with('success', 'Status updated');
    }
}
