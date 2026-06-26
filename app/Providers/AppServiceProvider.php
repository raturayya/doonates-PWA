<?php

namespace App\Providers;

use App\Services\AI\OpenRouterDonationAIService;
use App\Services\AI\Contracts\DonationAIContract;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * To swap AI provider:
     * - Anthropic Claude : bind ClaudeDonationAIService
     * - OpenRouter (free): bind OpenRouterDonationAIService  ← aktif sekarang
     */
    public function register(): void
    {
        $this->app->bind(DonationAIContract::class, OpenRouterDonationAIService::class);
    }

    public function boot(): void
    {
        //
    }
}