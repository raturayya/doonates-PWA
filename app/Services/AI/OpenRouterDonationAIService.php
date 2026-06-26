<?php

namespace App\Services\AI;

use App\Services\AI\Contracts\DonationAIContract;
use App\Services\AI\Exceptions\AIGenerationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterDonationAIService implements DonationAIContract
{
    private const API_URL = 'https://openrouter.ai/api/v1/chat/completions';

    // Model gratis di OpenRouter (tidak perlu bayar)
    // Ganti model di sini jika ingin coba model lain
    private const MODEL = 'meta-llama/llama-3.3-70b-instruct:free';

    public function generate(string $foodName, ?string $category): DonationAIResult
    {
        $apiKey = config('services.openrouter.key');

        if (empty($apiKey)) {
            throw new AIGenerationException('OpenRouter API key is not configured.');
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json',
            'HTTP-Referer'  => config('app.url', 'http://localhost'),
            'X-Title'       => 'Doonates',
        ])->timeout(30)->post(self::API_URL, [
            'model' => self::MODEL,
            'messages' => [
                ['role' => 'system', 'content' => $this->systemPrompt()],
                ['role' => 'user',   'content' => $this->userPrompt($foodName, $category)],
            ],
            'max_tokens'  => 1024,
            'temperature' => 0.7,
        ]);

        if ($response->failed()) {
            Log::error('OpenRouter API error', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            throw new AIGenerationException(
                'AI service returned an error. Please try again later.'
            );
        }

        $raw = $response->json('choices.0.message.content', '');

        if (empty($raw)) {
            Log::error('OpenRouter empty response', ['body' => $response->body()]);
            throw new AIGenerationException(
                'AI returned an empty response. Please try again.'
            );
        }

        return $this->parseResponse($raw);
    }

    // -------------------------------------------------------------------------
    // Prompts — edit here to tune AI output without touching any other file
    // -------------------------------------------------------------------------

    private function systemPrompt(): string
    {
        return <<<'PROMPT'
You are a food donation assistant for Doonates, a platform that connects food donors
with communities in need. Your job is to generate professional, clear, and helpful
donation information in English.

You must respond ONLY with a valid JSON object — no markdown, no explanation, no extra text.

The JSON must have exactly these keys:
- "description": string — 2-3 sentence professional description of the food item,
  suitable for a donation listing. Mention it is freshly prepared or recently packaged.
- "allergens": array of strings — list only likely allergens present in this food.
  Use these standard labels only: Eggs, Milk, Soy, Peanuts, Tree Nuts, Chicken,
  Seafood, Gluten, Sesame, Shellfish. If none apply, return an empty array.
- "storage_recommendation": string — 1-2 sentences on how to store the food.
- "shelf_life": string — estimated safe shelf life at room temperature (e.g. "2-4 hours").
- "handling_recommendation": string — 1-2 sentences on safe handling and serving.
PROMPT;
    }

    private function userPrompt(string $foodName, ?string $category): string
    {
        $categoryLine = $category
            ? "Category: {$category}"
            : 'Category: not specified';

        return <<<PROMPT
Generate donation information for the following food item:

Food Name: {$foodName}
{$categoryLine}

Respond with a JSON object only.
PROMPT;
    }

    // -------------------------------------------------------------------------
    // Response parsing
    // -------------------------------------------------------------------------

    private function parseResponse(string $raw): DonationAIResult
    {
        // Strip accidental markdown fences
        $json = preg_replace('/^```(?:json)?\s*/i', '', trim($raw));
        $json = preg_replace('/\s*```$/', '', $json);

        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($data)) {
            Log::error('Failed to parse OpenRouter JSON response', ['raw' => $raw]);
            throw new AIGenerationException(
                'AI returned an unexpected response format. Please try again.'
            );
        }

        return DonationAIResult::fromArray($data);
    }
}
