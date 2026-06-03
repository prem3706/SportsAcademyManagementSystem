<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = ['name', 'slug', 'status'];

    public function sports()
    {
        return $this->belongsToMany(Sport::class, 'sports_levels')
            ->withPivot('fees');
    }

    public function playerFees()
    {
        return $this->hasMany(PlayerFee::class);
    }
}
