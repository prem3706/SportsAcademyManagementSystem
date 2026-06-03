<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeesGenerate extends Model
{
    protected $fillable = [

        'month',

        'year',

        'status',

        'generated_by',

    ];

    public function playerFees()
    {
        return $this->hasMany(PlayerFee::class);
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
