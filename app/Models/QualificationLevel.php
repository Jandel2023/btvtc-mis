<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualificationLevel extends Model
{
    //
    public function qualifications()
    {
        return $this->hasMany(Qualifications::class);
    }
}
