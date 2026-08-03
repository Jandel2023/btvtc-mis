<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TrainingSector;

class TrainingSectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        TrainingSector::insert([

            ['sector_name' => 'ICT'],

            ['sector_name' => 'Automotive'],

            ['sector_name' => 'Construction'],

            ['sector_name' => 'Tourism'],

            ['sector_name' => 'Agriculture'],

            ['sector_name' => 'Garments'],

            ['sector_name' => 'Electronics'],

            ['sector_name' => 'Food Processing'],

        ]);
    }
}
