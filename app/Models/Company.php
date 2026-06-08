<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name', 'rfc', 'email', 'phone', 'address', 'active',
        'stripe_customer_id', 'logo', 'industry', 'employee_count'
    ];

    protected $casts = [
        'active' => 'boolean',
        'employee_count' => 'integer',
    ];

    public function subscription()
    {
        return $this->hasOne(Subscription::class)->latestOfMany(); // la más reciente
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function plan()
    {
        return $this->hasOneThrough(Plan::class, Subscription::class)
                    ->latestOfMany('subscriptions.created_at'); // o usa el helper abajo
    }

    // Accesores útiles para el frontend
    public function getCurrentPlanAttribute()
    {
        return $this->subscription?->plan;
    }

    public function getStatusAttribute()
    {
        return $this->subscription?->status ?? 'inactive';
    }

    public function getExpiresAtAttribute()
    {
        return $this->subscription?->current_period_end?->format('d/m/Y');
    }
}