<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PushSubscriptionController extends Controller
{
    /**
     * Store or update a push subscription for the authenticated user.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'endpoint'   => 'required|string',
            'public_key' => 'required|string',
            'auth_token' => 'required|string',
        ]);

        // updateOrCreate so re-subscribing the same device doesn't duplicate
        PushSubscription::updateOrCreate(
            ['endpoint' => $request->endpoint],
            [
                'user_id'    => Auth::id(),
                'public_key' => $request->public_key,
                'auth_token' => $request->auth_token,
            ]
        );

        return response()->json(['status' => 'subscribed'], 201);
    }

    /**
     * Remove a push subscription (user unsubscribed on device).
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate(['endpoint' => 'required|string']);

        PushSubscription::where('endpoint', $request->endpoint)
            ->where('user_id', Auth::id())
            ->delete();

        return response()->json(['status' => 'unsubscribed']);
    }
}
