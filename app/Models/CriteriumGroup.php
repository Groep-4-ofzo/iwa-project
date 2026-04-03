<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CriteriumGroup extends Model
{
    protected $table = 'criterium_group';

    public $timestamps = false;

    protected $fillable = [
        'group_level',
        'operator'
    ];

    public function criteriumType()
    {
        return $this->belongsTo(CriteriumType::class, 'type');
    }

    public function queries()
    {
        return $this->belongsTo(Query::class, 'query');
    }
}
