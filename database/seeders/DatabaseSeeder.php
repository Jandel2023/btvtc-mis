<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\UserRole;


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
        ]);

        User::factory()->create([
            'name' => 'jandel',
            'email' => 'jandellopez1997@gmail.com',
            'role' => UserRole::Administrator,
        ]);
    }
}
