<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'first_name',
        'initials',
        'prefix',
        'email',
        'employee_code',
        'user_role',
        'password',
    ];

    protected $hidden = [
        'password',        
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function userRole(): BelongsTo
    {
        return $this->belongsTo(UserRole::class, 'user_role');
    }

    public function hasRole(string $role): bool
    {
        return $this->userRole->role === $role;
    }
}