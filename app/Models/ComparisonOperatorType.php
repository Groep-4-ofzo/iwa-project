<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComparisonOperatorType extends Model
{
    protected $table = 'comparison_operator_type';

    public $timestamps = false;

    protected $fillable = [
        'omschrijving'
    ];
}
