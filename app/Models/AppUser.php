<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppUser extends Model
{
    //
    protected $table = 'app_users';

    public $timestamps = false;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'identifier',
        'password',
        'role',
        'contract_id',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

}
