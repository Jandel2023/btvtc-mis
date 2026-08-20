<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReleaseToolkit extends Model
{
    //
    protected $guarded = [];
    
  public function qualification()
    {
        return $this->belongsTo(Qualifications::class);
    }

    public function screening()
    {
        return $this->belongsTo(screening::class);
    }
}
