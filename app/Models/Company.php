<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    //
    protected $fillable = [
      'name',
      'city',
      'street',
      'number',
      'number_additional',
      'zip_code',
      'email'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country');
    }
}
