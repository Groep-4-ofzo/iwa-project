<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    protected $table = 'query';

    public $timestamps = false;

    protected $fillable = [
        'contract_id',
        'omschrijving',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function groups()
    {
        return $this->hasMany(CriteriumGroup::class, 'query');
    }
}
