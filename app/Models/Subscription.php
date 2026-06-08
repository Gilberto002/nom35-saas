<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'company_id', 'plan_id', 'status', 'trial_ends_at',
        'current_period_start', 'current_period_end', 'cancelled_at',
        'payment_method', 'external_id', 'notes', 'stripe_subscription_id'
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    // Helpers útiles
    public function isActive(): bool
    {
        return $this->status === 'active' && 
               ($this->cancelled_at === null || $this->cancelled_at->isFuture());
    }

    public function isTrial(): bool
    {
        return $this->status === 'trial';
    }
}