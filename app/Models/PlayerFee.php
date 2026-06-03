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

        'amount',

        'status',

        'generated_at',

        'paid_at',

    ];

    public function feesGenerate()
    {
        return $this->belongsTo(FeesGenerate::class);
    }

    public function player()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}
