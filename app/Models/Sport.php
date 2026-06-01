<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'status'];

    public function levels()
    {
        return $this->belongsToMany(Level::class, 'sports_levels')
            ->withPivot('fees');
    }
}
