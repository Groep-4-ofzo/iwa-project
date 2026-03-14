<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OriginalMeasurement extends Model
{
    //
    protected $table = 'original_measurement';
    protected $fillable = [
        'missing_field',
        'invalid_temperature'
    ];

    public function measurement()
    {
        return $this->belongsTo(Measurement::class, 'measurement');
    }
}
