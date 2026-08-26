<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReleaseToolkit extends Model
{
    protected $guarded = [];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function qualification()
    {
        return $this->belongsTo(Qualifications::class);
    }

    public function screening()
    {
        return $this->belongsTo(Screening::class);
    }
}
