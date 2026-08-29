<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Jandel Lopez',
                'email' => 'jandellopez1997@gmail.com',
                'password' => Hash::make('admin'),
                'role' => UserRole::SuperAdmin,
                'qualification_id' => null,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Administrator',
                'email' => 'administrator@example.com',
                'password' => Hash::make('password'),
                'role' => UserRole::Administrator,
                'qualification_id' => null,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Registrar',
                'email' => 'registrar@example.com',
                'password' => Hash::make('password'),
                'role' => UserRole::Registrar,
                'qualification_id' => null,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Trainer One',
                'email' => 'trainer1@example.com',
                'password' => Hash::make('password'),
                'role' => UserRole::Trainer,
                'qualification_id' => 1,
                'email_verified_at' => now(),
            ],
          
        ]);

   
    }
}
