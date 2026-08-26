<?php

namespace Database\Seeders;

use App\Models\Screening;
use Illuminate\Database\Seeder;

class ScreeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Screening::insert([
            [
                'fname' => 'Maria',
                'lname' => 'Santos',
                'mname' => 'Reyes',
                'aptitude_score' => 27,
                'interview_score' => 88,
                'total_score' => null,
                'phone' => '09171234567',
                'batch_id' => 1,
                'address' => 'Barangay Poblacion, Baybay City',
                'date_screened' => now()->subDays(5)->toDateString(),
                'remarks' => 'Recommended for enrollment.',
                'screened_by' => null,
            ],

        ]);

        // foreach ($screenings as $index => $screening) {
        //     Screening::query()->create([
        //         ...$screening,
        //         'qualification_id' => $qualificationIds[$index % $qualificationIds->count()],
        //     ]);
        // }
    }
}
