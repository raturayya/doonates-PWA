<?php

namespace App\Http\Controllers;

use App\Services\AI\Contracts\DonationAIContract;
use App\Services\AI\Exceptions\AIGenerationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DonationAIController extends Controller
{
    private DonationAIContract $ai;

    public function __construct(DonationAIContract $ai)
    {
        $this->ai = $ai;
    }

    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'food_name' => 'required|string|max:255',
            'category'  => 'nullable|string|max:100',
        ]);

        try {
            $result = $this->ai->generate(
                foodName: trim($request->input('food_name', '')),
                category: trim($request->input('category', '')) ?: null,
            );

            return response()->json([
                'success' => true,
                'data'    => $result->toArray(),
            ]);
        } catch (AIGenerationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}