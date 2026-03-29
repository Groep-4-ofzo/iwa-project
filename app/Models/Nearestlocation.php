<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nearestlocation extends Model
{
    //
    protected $table = 'nearestlocation';
    protected $fillable = [
        'name',
        'administrative_region1',
        'administrative_region2',
        'longitude',
        'latitude'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'country_code');
    }

    public function station()
    {
        return $this->belongsTo(Station::class, 'station');
    }
}
