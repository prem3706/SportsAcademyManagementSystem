<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = [

        'name',

        'capacity',

        'start_time',

        'end_time',

        'sport_id',

        'level_id',

        'status',

    ];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function coaches()
    {
        return $this->belongsToMany(
            User::class,
            'batch_coach',
            'batch_id',
            'coach_id'
        );
    }

    public function players()
    {
        return $this->belongsToMany(
            User::class,
            'batch_player',
            'batch_id',
            'player_id'
        )->withPivot('joined_at');
    }

    /**
     * Check if the batch has reached its player capacity.
     *
     * @param int|null $excludePlayerId Exclude this player's ID from count (for updates)
     * @return bool
     */
    public function isFull(?int $excludePlayerId = null): bool
    {
        if (is_null($this->capacity)) {
            return false;
        }

        $query = $this->players();
        if ($excludePlayerId) {
            $query = $query->where('users.id', '!=', $excludePlayerId);
        }

        return $query->count() >= $this->capacity;
    }
}
