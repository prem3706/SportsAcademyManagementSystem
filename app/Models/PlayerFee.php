<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerFee extends Model
{
    protected $fillable = [

        'fees_generate_id',

        'user_id',

        'sport_id',

        'level_id',

        'month',

        'year',

        'amount',

        'status',

        'generated_at',

        'paid_at',

    ];

    protected $casts = [

        'generated_at' => 'datetime',

        'paid_at' => 'datetime',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function feesGenerate()
    {
        return $this->belongsTo(FeesGenerate::class);
    }
}
