<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionStation extends Model
{
    //
    protected $table = 'subscription_station';
    protected $fillable = ['station', 'subscription'];
    public $timestamps = false;
    public function getStation()
    {
        return $this->belongsTo(Station::class, 'station', 'name');
    }

    public function getSubscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription', 'id');
    }
}
