<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'company_id',
        'plan_id', 
        'status',
        'trial_ends_at',
        'current_period_start',
        'current_period_end',
    ];
}