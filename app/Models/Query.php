<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    protected $table = 'query';

    public $timestamps = false;

    protected $fillable = [
        'omschrijving',
    ];

    public function contract()
    {
        return $this->belongsTo('App\Models\Contract');
    }
}
