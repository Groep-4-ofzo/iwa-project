<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Geolocation extends Model
{
    //
    protected $table = 'geolocation';

    protected $fillable = [
      'island',
      'county',
      'place',
      'hamlet',
      'town',
      'municipality',
      'state_district',
      'administrative',
      'state',
      'village',
      'region',
      'province',
      'city',
      'locality',
      'postcode',
      'country'
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
