<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['firstname', 'lastname', 'email', 'password', 'phone', 'profile_picture', 'gender', 'role', 'status', 'joined_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'phone',
        'profile_picture',
        'gender',
        'role',
        'status',
        'joined_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];

    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function coachBatches()
    {
        return $this->belongsToMany(
            Batch::class,
            'batch_coach',
            'coach_id',
            'batch_id'
        );
    }

    public function playerBatches()
    {
        return $this->belongsToMany(
            Batch::class,
            'batch_player',
            'player_id',
            'batch_id'
        )->withPivot('joined_at');
    }

    public function playerFees()
    {
        return $this->hasMany(PlayerFee::class);
    }
}
