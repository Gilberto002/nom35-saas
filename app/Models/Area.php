<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\BelongsToCompany;

class Area extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'name',
        'description',
        'company_id',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
