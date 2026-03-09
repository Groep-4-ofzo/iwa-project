<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nearestlocation extends Model
{
    //
    protected $fillable = [
        'name',
        'administrative_region1',
        'administrative_region2',
        'longitude',
        'latitude'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country');
    }

    public function station()
    {
        return $this->belongsTo(Station::class, 'station');
    }
}
