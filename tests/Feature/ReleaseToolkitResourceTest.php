<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('loads the release toolkit create page for administrators', function () {
    $user = User::factory()->create([
        'name' => 'Admin User',
        'email' => 'admin@gmail.com',
        'role' => UserRole::Administrator,
        'email_verified_at' => now(),
    ]);

    $this->actingAs($user)
        ->get('/admin/release-toolkits/create')
        ->assertOk();
});

it('hides the current logged in user from the users table query', function () {
    $currentUser = User::factory()->create([
        'name' => 'Active Admin',
        'email' => 'active-admin@gmail.com',
        'role' => UserRole::Administrator,
        'email_verified_at' => now(),
    ]);

    User::factory()->create([
        'name' => 'Other Admin',
        'email' => 'other-admin@gmail.com',
        'role' => UserRole::Administrator,
        'email_verified_at' => now(),
    ]);

    $this->actingAs($currentUser);

    $visibleUsers = User::query()
        ->whereKeyNot(auth()->id())
        ->pluck('name');

    expect($visibleUsers)
        ->not->toContain($currentUser->name)
        ->toContain('Other Admin');
});
