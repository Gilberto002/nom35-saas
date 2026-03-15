<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    protected $fillable = [
        'name',
        'email',
        'phone',
        'rfc',
        'address',
        'active',
        'logo',
        'industry',
        'employee_count',
        'last_activity_at'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

}