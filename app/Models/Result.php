<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'evaluation_id',
        'score',
        'risk_level'
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
}