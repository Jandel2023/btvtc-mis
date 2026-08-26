<?php

namespace Database\Seeders;

use App\Models\Qualifications;
use Illuminate\Database\Seeder;

class QualificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Qualifications::insert([
            [
                'qualification_code' => 'Employee',
                'qualification_title' => 'Baybay City Employee',
                'qualification_level_id' => 1,
                'training_sector_id' => 1,
                'training_hours' => 1,
                'competency_standard' => '0',
                'description' => '0',
                'is_active' => false,
            ],
            [
                'qualification_code' => 'SMAW',
                'qualification_title' => 'Shielded Metal Arc Welding',
                'qualification_level_id' => 1,
                'training_sector_id' => 9,
                'training_hours' => 268,
                'competency_standard' => 'sample',
                'description' => 'sample',
                'is_active' => true,
            ],

            [
                'qualification_code' => 'MSES',
                'qualification_title' => 'Motorcycle/Small Engine Servicing',
                'qualification_level_id' => 2,
                'training_sector_id' => 2,
                'training_hours' => 650,
                'competency_standard' => 'sample',
                'description' => 'sample',
                'is_active' => true,
            ],

            [
                'qualification_code' => 'BPP',
                'qualification_title' => 'Bread and Pastry Production',
                'qualification_level_id' => 2,
                'training_sector_id' => 4,
                'training_hours' => 141,
                'competency_standard' => 'sample',
                'description' => 'sample',
                'is_active' => true,
            ],

            [
                'qualification_code' => 'EIM',
                'qualification_title' => 'Electrical Installation and Maintenance',
                'qualification_level_id' => 2,
                'training_sector_id' => 7,
                'training_hours' => 196,
                'competency_standard' => 'sample',
                'description' => 'sample',
                'is_active' => true,
            ],

        ]);
    }
}
