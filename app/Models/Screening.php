<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Screening extends Model
{
    //
    protected $guarded = [];

    /**
     * Get the applicant's complete name.
     */
    public function getFullNameAttribute(): string
    {
        return implode(' ', array_filter([
            $this->fname,
            $this->mname,
            $this->lname,
        ]));
    }

    public function qualification()
    {
        return $this->belongsTo(Qualifications::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
