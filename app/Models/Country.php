<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    //According to stackoverflow, these three are needed seeing as the PK is not id.
    protected $primaryKey = 'country_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'country_code',
        'country'
    ];
}
