<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $table = 'contract';

    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'omschrijving',
        'start_datum',
        'eind_datum',
        'url',
    ];

}
