<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Measurement extends Model
{
    //
    public $timestamps = false;

    protected $table = "measurement";
    protected $fillable = [
        "station",
        "date",
        "time",
        "temperature",
        "dewpoint_temperature",
        "air_pressure_station",
        "air_pressure_sea_level",
        "visibility",
        "wind_speed",
        "percipation",
        "snow_depth",
        "conditions",
        "cloud_cover",
        "wind_direction",
    ];

    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class, "station");
    }
}
