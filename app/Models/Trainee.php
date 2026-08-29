<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainee extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        static::deleted(function (self $trainee) {
            if ($trainee->screening) {
                $trainee->screening->update([
                    'enrolled_status' => false,
                ]);
            }
        });
    }

    public function screening()
    {
        return $this->belongsTo(Screening::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function qualification()
    {
        return $this->belongsTo(Qualifications::class);
    }
}
