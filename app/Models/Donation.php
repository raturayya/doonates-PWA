<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'food_name',
        'category',
        'quantity',
        'original_stock',
        'remaining_stock',
        'total_taken',
        'unit_id',
        'expiry_date',
        'pickup_time',
        'description',
        'status',
        'organization_name',
    ];

    public function requests()
    {
        return $this->hasMany(RequestDonation::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper: get original stock (fallback to quantity)
    public function getOriginalStockAttribute($value)
    {
        return $value ?? $this->attributes['quantity'] ?? 0;
    }

    // Helper: get remaining stock (fallback to remaining_quantity)
    public function getRemainingStockAttribute($value)
    {
        return $value ?? $this->attributes['quantity'] ?? 0;
    }
}
