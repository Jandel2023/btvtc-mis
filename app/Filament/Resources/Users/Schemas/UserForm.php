<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use app\Enums\UserRole;
use Filament\Forms\Components\asSelectArray;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                Select::make('role')
                    ->label('User Role')
                    ->options(UserRole::class)
                    ->required(),
                DateTimePicker::make('email_verified_at')
                    ->label('Email Verified At')
                    ->default(fn () => now())
                    ->nullable(),
                TextInput::make('password')
                    ->password()
                    ->default(fn () => \Illuminate\Support\Facades\Hash::make('admin'))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required()
                    ->hidden(
                        fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord
                    ),
            ]);
    }
}
