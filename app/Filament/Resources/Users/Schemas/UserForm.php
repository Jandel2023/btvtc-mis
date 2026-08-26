<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Username')
                    ->required(),

                Select::make('role')
                    ->label('User role')
                    ->options(UserRole::class)
                  // ->default(UserRole::Trainer->value) // = 'trainer'
                    ->live()
                    ->required(),

                Select::make('qualification_id')
                    ->label('Qualification')
                    ->relationship('qualification', 'qualification_code')
                    ->visible(fn (Get $get) => $get('role') === UserRole::Trainer->value || $get('role') === UserRole::Trainer)
                    ->required(fn (Get $get) => $get('role') === UserRole::Trainer->value)
                    ->live(),

                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->unique('users', 'email', ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'This email address is already in use.',
                    ]),
                DateTimePicker::make('email_verified_at')
                    ->default(now())
                    ->visible(fn (?User $record) => $record !== null),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->required()
                    ->confirmed(),
                TextInput::make('password_confirmation')
                    ->label('Confirm password')
                    ->password()
                    ->revealable()
                    ->required()
                    ->dehydrated(false),
                TextInput::make('phone')
                    ->tel()
                    ->numeric()
                    ->maxLength(11)
                    ->placeholder('09XXXXXXXXX')
                    ->helperText('Philippine mobile number format: 09XXXXXXXXX')
                    ->rules(['regex:/^09\d{9}$/']),
                DatePicker::make('dob')
                    ->label('Birthdate')
                    ->rules(['date', 'before:today'])
                    ->validationMessages([
                        'before:today' => 'Birthdate must be before today.',
                    ]),
                TextInput::make('id_number')
                    ->label('ID # given from LGU BAYBAY'),

            ]);
    }
}
