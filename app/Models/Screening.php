<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Screening extends Model
{
    //
    protected $guarded = [];

    protected static function booted(): void
    {
        static::updated(function (self $screening) {
            if (! $screening->wasChanged('batch_id') && ! $screening->wasChanged('fname') && ! $screening->wasChanged('mname') && ! $screening->wasChanged('lname') && ! $screening->wasChanged('phone')) {
                return;
            }

        });
    }

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
