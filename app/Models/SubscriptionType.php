<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionType extends Model
{
    //
    protected $fillable = [
        'name',
        'nr_stations',
        'frequency_in_hours',
        'frequency_in_days',
        'continuous',
        'price_per_station',
        'valid_through'
    ];
}
