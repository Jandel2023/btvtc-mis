<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    //
    protected $guarded = [];

    public function qualification()
    {
        return $this->belongsTo(qualifications::class);
    }

    public function ntp()
    {
        return $this->belongsTo(Ntp::class);
    }

    public function screenings()
    {
        return $this->hasMany(Screening::class);
    }
}
