<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qualifications extends Model
{
//
    protected $guarded = [];

    public function qualificationLevel()
    {
        return $this->belongsTo(QualificationLevel::class);
    }

    public function trainingSector()
    {
        return $this->belongsTo(TrainingSector::class);
    }

    public function releaseToolkits()
    {
        return $this->hasMany(ReleaseToolkit::class);
    }

   
}
