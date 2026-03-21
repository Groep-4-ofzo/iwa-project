<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    //
    public $timestamps = false;
    protected $table = 'subscriptions';
    protected $fillable = [
        'start_date',
        'end_date',
        'price',
        'notes',
        'identifier',
        'token',
        'company',
        'type'
    ];

    public function getCompany() // moet ik nog bespreken
    {
        return $this->belongsTo(Company::class, 'company');
    }

    public function subscriptionType()
    {
        return $this->belongsTo(SubscriptionType::class, 'type');
    }
}
