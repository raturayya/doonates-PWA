<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestDonation extends Model
{
    protected $fillable = [
        'donation_id',
        'organization_name',
        'organization_type',
        'requested_quantity',
        'pickup_time',
        'message',
        'status',
        'user_id',
        'pickup_latitude',
        'pickup_longitude',
    ];

    public function isFinished(): bool
    {
        return $this->status === 'Finished';
    }

    public function hasLocation(): bool
    {
        return !is_null($this->pickup_latitude) && !is_null($this->pickup_longitude);
    }

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
