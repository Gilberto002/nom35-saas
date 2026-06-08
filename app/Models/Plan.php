<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'price', 'interval', 'max_employees',
        'max_reports_per_month', 'api_access', 'features', 'active'
    ];

    protected $casts = [
        'features' => 'array',        // si guardas JSON
        'price' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}