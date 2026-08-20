<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ntp extends Model
{
    //
    protected $guarded = [];

    public function qualification()
    {
        return $this->belongsTo(qualifications::class);
    }

    public function batches()
    {
        return  $this->hasMany(Batch::class);
    }
}
