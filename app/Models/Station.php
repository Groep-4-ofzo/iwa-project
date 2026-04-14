<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $table = 'station';

    protected $primaryKey = 'name';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'longitude',
        'latitude',
        'elevation',
    ];

    public function geolocation()
    {
        return $this->hasMany(Geolocation::class, 'station_name', 'name');
    }

    public function nearestlocation()
    {
        return $this->hasMany(Nearestlocation::class, 'station_name', 'name');
    }

    public function measurements()
    {
        return $this->hasMany(Measurement::class, 'station', 'name');
    }
}
