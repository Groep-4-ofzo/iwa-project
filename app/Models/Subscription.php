<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    //
    protected $fillable = [
        'start_date',
        'end_date',
        'price',
        'notes',
        'identifier',
        'token'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'companies');
    }

    public function subscriptionType()
    {
        return $this->belongsTo(SubscriptionType::class, 'subscription_types');
    }
}
