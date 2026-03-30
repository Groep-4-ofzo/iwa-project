<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OriginalMeasurement extends Model
{
    public $timestamps = false;

    protected $table = "original_measurement";

    protected $fillable = ["corrected_measurement", "missing_field", "invalid_temperature"];

    public function measurement(): BelongsTo
    {
        return $this->belongsTo(Measurement::class, 'corrected_measurement');
    }
}
