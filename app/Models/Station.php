<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    //Same as Country.php
    protected $table = 'station';
    protected $primaryKey = 'name';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
      'name',
      'longitude',
      'latitude',
      'elevation'
    ];
}
