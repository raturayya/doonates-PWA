<?php

namespace App\Services\AI;

use App\Services\AI\Contracts\DonationAIContract;
use App\Services\AI\Exceptions\AIGenerationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterDonationAIService implements DonationAIContract
{
    private const API_URL = 'https://openrouter.ai/api/v1/chat/completions';

    // Daftar model gratis di OpenRouter, dicoba berurutan.
    // Kalau model pertama kena rate-limit (429) dari provider upstream,
    // otomatis lanjut ke model berikutnya di list ini.
    // Update list ini sewaktu-waktu sesuai model gratis yang tersedia di openrouter.ai/models?max_price=0
    private const MODELS = [
        'meta-llama/llama-3.3-70b-instruct:free',
        'openai/gpt-oss-20b:free',
        'deepseek/deepseek-r1:free',
        'openrouter/free', // free model router - OpenRouter pilih sendiri model yang sedang tersedia
    ];

    // Berapa kali retry per model kalau upstream balas 429 (rate-limited sementara)
    private const MAX_RETRIES_PER_MODEL = 1;

    public function generate(string $foodName, ?string $category): DonationAIResult
    {
        $apiKey = config('services.openrouter.key');

        if (empty($apiKey)) {
            throw new AIGenerationException('OpenRouter API key is not configured.');
        }

        $lastStatus = null;
        $lastBody   = null;

        foreach (self::MODELS as $model) {
            for ($attempt = 0; $attempt <= self::MAX_RETRIES_PER_MODEL; $attempt++) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                    'HTTP-Referer'  => config('app.url', 'http://localhost'),
                    'X-Title'       => 'Doonates',
                ])->timeout(30)->post(self::API_URL, [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $this->systemPrompt()],
                        ['role' => 'user',   'content' => $this->userPrompt($foodName, $category)],
                    ],
                    'max_tokens'  => 1024,
                    'temperature' => 0.7,
                ]);

                if ($response->successful()) {
                    $raw = $response->json('choices.0.message.content', '');

                    if (empty($raw)) {
                        Log::error('OpenRouter empty response', ['model' => $model, 'body' => $response->body()]);
                        throw new AIGenerationException(
                            'AI returned an empty response. Please try again.'
                        );
                    }

                    return $this->parseResponse($raw);
                }

                $lastStatus = $response->status();
                $lastBody   = $response->body();

                // Rate-limited upstream (429) -> tunggu sebentar lalu retry,
                // baru kalau masih gagal pindah ke model berikutnya.
                if ($lastStatus === 429) {
                    Log::warning('OpenRouter model rate-limited, trying fallback', [
                        'model' => $model,
                        'attempt' => $attempt,
                        'body' => $lastBody,
                    ]);

                    $retryAfter = $response->json('error.metadata.retry_after_seconds');
                    if ($attempt < self::MAX_RETRIES_PER_MODEL) {
                        sleep(min((int) ceil($retryAfter ?? 3), 10));
                        continue; // retry model yang sama
                    }

                    break; // habis retry untuk model ini, lanjut ke model berikutnya
                }

                // Error selain 429 (401, 500, dll) -> tidak ada gunanya retry/ganti model
                Log::error('OpenRouter API error', [
                    'model'  => $model,
                    'status' => $lastStatus,
                    'body'   => $lastBody,
                ]);
                throw new AIGenerationException(
                    'AI service returned an error. Please try again later.'
                );
            }
        }

        // Semua model di list kena rate-limit
        Log::error('All OpenRouter free models rate-limited', [
            'status' => $lastStatus,
            'body'   => $lastBody,
        ]);
        throw new AIGenerationException(
            'AI service is currently busy (rate-limited). Please try again in a moment.'
        );
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