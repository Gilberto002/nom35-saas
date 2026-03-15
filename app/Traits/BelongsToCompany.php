<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToCompany
{
    protected static function bootBelongsToCompany()
    {
        static::creating(function ($model) {

            if (Auth::check()) {
                $model->company_id = Auth::user()->company_id;
            }

        });

        static::addGlobalScope('company', function (Builder $builder) {

            if (Auth::check()) {
                $builder->where('company_id', Auth::user()->company_id);
            }

        });
    }
}