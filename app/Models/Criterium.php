<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criterium extends Model
{
    // De tabelnaam is 'criterium'
    protected $table = 'criterium';

    public $timestamps = false;

    // Deze velden horen hier, omdat dit de data-rijen zijn
    protected $fillable = [
        'group', 
        'operator', 
        'int_value', 
        'string_value', 
        'float_value', 
        'value_type', 
        'value_comparison'
    ];

    public function groupRelation()
    {
        return $this->belongsTo(CriteriumGroup::class, 'group');
    }

    public function type()
    {
        return $this->belongsTo(CriteriumType::class, 'value_type');
    }

    public function comparison()
    {
        return $this->belongsTo(ComparisonOperatorType::class, 'value_comparison');
    }
}
