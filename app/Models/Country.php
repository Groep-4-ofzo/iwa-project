<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $primaryKey = 'country_code';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'country';

    protected $fillable = [
        'country_code',
        'country',
    ];
}
