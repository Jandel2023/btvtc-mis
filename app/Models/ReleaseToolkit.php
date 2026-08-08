<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReleaseToolkit extends Model
{
    //
    use SoftDeletes;
    
    protected $guarded = [];

     public function qualifications()
    {
        return $this->hasMany(Qualifications::class);
    }
}
