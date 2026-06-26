<?php

namespace App\Services\AI\Contracts;

use App\Services\AI\DonationAIResult;

interface DonationAIContract
{
    /**
     * Generate donation information from a food name and optional category.
     *
     * @throws \App\Services\AI\Exceptions\AIGenerationException
     */
    public function generate(string $foodName, ?string $category): DonationAIResult;
}
