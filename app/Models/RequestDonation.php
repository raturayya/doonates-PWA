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
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
