<?php

namespace Database\Seeders;

use App\Enums\ScholarshipProgram;
use App\Models\Qualifications;
use App\Models\Screening;
use Illuminate\Database\Seeder;

class ScreeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $qualificationIds = Qualifications::query()->pluck('id')->values();

        $screenings = [
            [
                'fname' => 'Maria',
                'lname' => 'Santos',
                'mname' => 'Reyes',
                'aptitude_score' => 27,
                'interview_score' => 88,
                'total_score' => null,
                'phone' => '09171234567',
                
                'address' => 'Barangay Poblacion, Baybay City',
                'date_screened' => now()->subDays(5)->toDateString(),
                'remarks' => 'Recommended for enrollment.',
                'screened_by' => 'Administrator',
            ],
          
        ];

        // foreach ($screenings as $index => $screening) {
        //     Screening::query()->create([
        //         ...$screening,
        //         'qualification_id' => $qualificationIds[$index % $qualificationIds->count()],
        //     ]);
        // }
    }
}
