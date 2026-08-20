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
                'scholarship_program' => ScholarshipProgram::TWSP->value,
                'address' => 'Barangay Poblacion, Baybay City',
                'date_screened' => now()->subDays(5)->toDateString(),
                'remarks' => 'Recommended for enrollment.',
                'screened_by' => 'Admin',
            ],
            [
                'fname' => 'John',
                'lname' => 'Dela Cruz',
                'mname' => 'Garcia',
                'aptitude_score' => 24,
                'interview_score' => 81,
                'total_score' => null,
                'phone' => '09181234567',
                'scholarship_program' => ScholarshipProgram::STEP->value,
                'address' => 'Barangay Gaas, Baybay City',
                'date_screened' => now()->subDays(4)->toDateString(),
                'remarks' => 'Meets the screening requirements.',
                'screened_by' => 'Admin',
            ],
            [
                'fname' => 'Anne',
                'lname' => 'Villanueva',
                'mname' => 'Lopez',
                'aptitude_score' => 22,
                'interview_score' => 76,
                'total_score' => null,
                'phone' => '09191234567',
                'scholarship_program' => ScholarshipProgram::TTSP->value,
                'address' => 'Barangay Caraycaray, Baybay City',
                'date_screened' => now()->subDays(3)->toDateString(),
                'remarks' => 'For final document verification.',
                'screened_by' => 'Admin',
            ],
            [
                'fname' => 'Carlo',
                'lname' => 'Mendoza',
                'mname' => 'Torres',
                'aptitude_score' => 20,
                'interview_score' => 72,
                'total_score' => null,
                'phone' => '09201234567',
                'scholarship_program' => ScholarshipProgram::LGU_Livelihood->value,
                'address' => 'Barangay Higulo, Baybay City',
                'date_screened' => now()->subDays(2)->toDateString(),
                'remarks' => 'Awaiting committee review.',
                'screened_by' => 'Admin',
            ],
            [
                'fname' => 'Liza',
                'lname' => 'Ramos',
                'mname' => 'Cruz',
                'aptitude_score' => 18,
                'interview_score' => 65,
                'total_score' => null,
                'phone' => '09211234567',
                'scholarship_program' => ScholarshipProgram::OTHER->value,
                'address' => 'Barangay Punta, Baybay City',
                'date_screened' => now()->subDay()->toDateString(),
                'remarks' => 'Needs additional guidance before enrollment.',
                'screened_by' => 'Admin',
            ],
        ];

        foreach ($screenings as $index => $screening) {
            Screening::query()->create([
                ...$screening,
                'qualification_id' => $qualificationIds[$index % $qualificationIds->count()],
            ]);
        }
    }
}
