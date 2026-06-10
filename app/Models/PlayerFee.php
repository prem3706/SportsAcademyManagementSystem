<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerFee extends Model
{
    protected $fillable = [
        'player_id',
        'sub_totalamount',
        'discount_amount',
        'total_amt',
        'start_date',
        'end_date',
        'payment_type',
        'upi_id',
        'img_upi',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function player()
    {
        return $this->belongsTo(User::class, 'player_id');
    }
}
