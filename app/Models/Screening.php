<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Screening extends Model
{
    //
    protected $guarded = [];

    public function qualification()
    {
        return $this->belongsTo(Qualifications::class);
    }
}
