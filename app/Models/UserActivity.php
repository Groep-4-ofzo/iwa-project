<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    //
    protected $table = 'useractivity';

    protected $fillable = [
        'endpoint_used',
        'files_downloaded',
        'activity_date',
        'activity_time',
        'authorized',
        'data_transferred'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }
}
