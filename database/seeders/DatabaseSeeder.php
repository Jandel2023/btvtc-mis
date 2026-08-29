<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            QualificationLevelSeeder::class,
            TrainingSectorSeeder::class,
            QualificationSeeder::class,
            NtpSeeder::class,
            BatchSeeder::class,
            ScreeningSeeder::class,
            UserSeeder::class,
        ]);
    }
}
