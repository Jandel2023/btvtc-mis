<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\QualificationLevel;

class QualificationLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        QualificationLevel::insert([

            [
                'code' => 'NC I',
                'name' => 'National Certificate I'
            ],

            [
                'code' => 'NC II',
                'name' => 'National Certificate II'
            ],

            [
                'code' => 'NC III',
                'name' => 'National Certificate III'
            ],

            [
                'code' => 'NC IV',
                'name' => 'National Certificate IV'
            ],

        ]);
    }
}
