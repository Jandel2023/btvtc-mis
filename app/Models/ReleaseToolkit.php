<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReleaseToolkit extends Model
{
    //
    protected $guarded = [];
    
  public function qualifications()
    {
        return $this->belongsTo(Qualifications::class, 'qualification_id');
    }
}
