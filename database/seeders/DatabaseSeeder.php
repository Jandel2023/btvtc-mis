<?php

namespace Database\Seeders;

use App\Enums\UserRole;
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
        // User::factory(10)->create();
        $this->call([
            QualificationLevelSeeder::class,
            TrainingSectorSeeder::class,
            QualificationSeeder::class,
            NtpSeeder::class,
            BatchSeeder::class,
            ScreeningSeeder::class,

        ]);

        User::factory()->create([
            'name' => 'jandel',
            'email' => 'jandellopez1997@gmail.com',
            'role' => UserRole::Administrator,
            'qualification_id' => null,
        ]);

        User::factory()->create([
            'name' => 'Jesse',
            'email' => 'jesse@gmail.com',
            'role' => UserRole::Administrator,
            'qualification_id' => null,
        ]);

        User::factory()->create([
            'name' => 'Harddiff',
            'email' => 'harddiff@gmail.com',
            'role' => UserRole::Registrar,
            'qualification_id' => null,
        ]);

        User::factory()->create([
            'name' => 'Syrill',
            'email' => 'syrill@gmail.com',
            'role' => UserRole::Trainer,
            'qualification_id' => 2,
        ]);

        User::factory()->create([
            'name' => 'Joseph',
            'email' => 'joseph@gmail.com',
            'role' => UserRole::Trainer,
            'qualification_id' => 3,
        ]);

        User::factory()->create([
            'name' => 'Ryl',
            'email' => 'ryl@gmail.com',
            'role' => UserRole::Trainer,
            'qualification_id' => 4,
        ]);

    }
}
