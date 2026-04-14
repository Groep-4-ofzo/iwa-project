<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CriteriumGroup extends Model
{
    protected $table = 'criterium_group';

    public $timestamps = false;

    protected $fillable = [
        'query',
        'type',
        'group_level',
        'operator',
    ];

    public function criteria()
    {
        return $this->hasMany(Criterium::class, 'group');
    }
}
