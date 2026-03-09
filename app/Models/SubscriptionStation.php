<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionStation extends Model
{
    //
    public function station()
    {
        return $this->belongsTo(Station::class, 'station');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscriptions');
    }
}
