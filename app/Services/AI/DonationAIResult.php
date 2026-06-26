<?php

namespace App\Services\AI;

class DonationAIResult
{
    public string $description;
    public array  $allergens;
    public string $storageRecommendation;
    public string $shelfLife;
    public string $handlingRecommendation;

    public function __construct(
        string $description,
        array  $allergens,
        string $storageRecommendation,
        string $shelfLife,
        string $handlingRecommendation
    ) {
        $this->description            = $description;
        $this->allergens              = $allergens;
        $this->storageRecommendation  = $storageRecommendation;
        $this->shelfLife              = $shelfLife;
        $this->handlingRecommendation = $handlingRecommendation;
    }

    /**
     * Build from a raw associative array (parsed from AI JSON response).
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['description']             ?? '',
            $data['allergens']               ?? [],
            $data['storage_recommendation']  ?? '',
            $data['shelf_life']              ?? '',
            $data['handling_recommendation'] ?? '',
        );
    }

    /**
     * Convert to plain array for JSON responses.
     */
    public function toArray(): array
    {
        return [
            'description'             => $this->description,
            'allergens'               => $this->allergens,
            'storage_recommendation'  => $this->storageRecommendation,
            'shelf_life'              => $this->shelfLife,
            'handling_recommendation' => $this->handlingRecommendation,
        ];
    }
}