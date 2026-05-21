<?php

namespace App\Models;

use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AppUser extends Authenticatable implements JWTSubject
{
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

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

    protected $hidden = [
        'password',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

}
