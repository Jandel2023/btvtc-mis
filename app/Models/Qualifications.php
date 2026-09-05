<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qualifications extends Model
{
//
    protected $guarded = [];

    protected static function generateQualificationCode(
    string $title,
    int $qualificationLevelId
): ?string {
    $acronym = collect(
        preg_split('/\s+/', trim($title))
    )
        ->filter()
        ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
        ->implode('');

    $level = QualificationLevel::find($qualificationLevelId);

    if (! $level) {
        return null;
    }

    $levelCode = strtoupper(
        str_replace(' ', '-', $level->code)
    );

    return "{$acronym}-{$levelCode}";
}

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

    public function screenings()
    {
        return $this->hasMany(Screening::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

 
    /**
     * A qualification can have many users.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function ntps()
    {
        return $this->hasMany(Ntp::class);
    }
   
   
}
