<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportsLevel extends Model
{
    protected $table = 'sports_levels';

    public $timestamps = false;

    protected $fillable = [
        'sport_id',
        'level_id',
        'fees',
    ];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}
