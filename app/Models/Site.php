<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $guarded = [];

    public function weatherLogs()
    {
        return $this->hasMany(WeatherLog::class);
    }

    public function delayLogs()
    {
        return $this->hasMany(DelayLog::class);
    }
}