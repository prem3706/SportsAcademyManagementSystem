<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = ['name', 'slug', 'status'];

    public function sports()
    {
        return $this->belongsToMany(Sport::class)
            ->withPivot('fees')
            ->withTimestamps();
    }
}
