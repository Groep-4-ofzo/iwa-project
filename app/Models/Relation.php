<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    //
    protected $table = 'relations';
    protected $fillable = [
        'name',
        'first_name',
        'initials',
        'prefix',
        'function',
        'title',
        'email',
        'phone'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'companies');
    }
}
