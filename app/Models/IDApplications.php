<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Enums\UserRole;
use App\Enums\ScholarshipProgram;



class IDApplications extends Model
{
    //
    protected $guarded = [];

    protected $table = 'i_d_applications';

    protected $casts = [
        'user_role' => UserRole::class,
        'scholarship_program' => ScholarshipProgram::class,
        'application_date' => 'date',
        'approved_at' => 'datetime',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function qualification(): BelongsTo
    {
        return $this->belongsTo(
            Qualifications::class,
            'qualification_id'
        );
    }
    public function idCard(): HasOne
    {
        return $this->hasOne(IDCard::class, 'i_d_application_id');
    }

    protected static function booted(): void
    {
        static::creating(function (IDApplications $application) {

            // Automatically set application date
            $application->application_date ??= now()->toDateString();

            // Generate application number
            $year = now()->year;

            $lastApplication = static::whereYear('created_at', $year)
                ->orderByDesc('id')
                ->first();

            $nextNumber = 1;

            if ($lastApplication) {
                $parts = explode(
                    '-',
                    $lastApplication->application_number
                );

                $lastNumber = end($parts);

                if (is_numeric($lastNumber)) {
                    $nextNumber = (int) $lastNumber + 1;
                }
            }

            $application->application_number = sprintf(
                'BTVTC-ID-%d-%06d',
                $year,
                $nextNumber
            );
        });
    }

    
}
