<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'evaluation_id',
        'question_id',
        'value',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
}