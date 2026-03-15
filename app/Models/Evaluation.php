<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $table = 'evaluations'; // por si acaso

    protected $fillable = [
        'company_id',
        'employee_id',
        'status',
        'guide',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function result()
    {
        return $this->hasOne(Result::class);
    }
}