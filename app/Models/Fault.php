<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fault extends Model
{
    protected $table = 'fault';

    protected $fillable = [
        'type_fault',
        'which_field',
        'corrected_data',
        'measurement'
    ];

    public function measurement()
    {
        return $this->belongsTo(Measurement::class, 'measurement');
    }
}
