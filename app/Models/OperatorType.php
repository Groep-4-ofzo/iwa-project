<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperatorType extends Model
{
    protected $table = 'operator_type';

    public $timestamps = false;

    protected $fillable = [
        'omschrijving'
    ];
}
