<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criterium extends Model
{
    protected $table = 'criterium_types';

    public $timestamps = false;

    protected $fillable = [
        'int_value',
        'str_value',
        'float_value',
        'value_type'
    ];

    public function group()
    {
        return $this->belongsTo(CriteriumGroup::class, 'group');
    }

    public function operator()
    {
        return $this->belongsTo(OperatorType::class, 'operator');
    }

    public function comparisonOperatorType()
    {
        return $this->belongsTo(ComparisonOperatorType::class, 'value_comparison');
    }
}
