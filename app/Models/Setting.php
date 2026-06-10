<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'allow_penalty',
        'penalty_days',
        'penalty_type',
        'penalty_amount',
        'discount_type',
        'discount_monthly',
        'discount_quarterly',
        'discount_half_yearly',
        'discount_yearly',
    ];
}
