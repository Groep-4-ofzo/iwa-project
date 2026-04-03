<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CriteriumType extends Model
{
    protected $table = 'criterium_type';

    public $timestamps = false;

    protected $fillable = [
        'omschrijving',
        'referenced_table',
        'referenced_field'
    ];
}
