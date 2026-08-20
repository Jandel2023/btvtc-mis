<?php

namespace App\Filament\Resources\IDApplications\Schemas;

use App\Enums\UserRole;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Factories\Sequence;
use App\Enums\ScholarshipProgram;

class IDApplicationsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            TextInput::make('application_number')
                ->label('Application Number')
                ->disabled()
                ->dehydrated(false)
                ->hidden(),

            Select::make('user_id')
                ->label('Applicant Name')
                ->relationship(
                    name: 'user',
                    titleAttribute: 'name',
                    modifyQueryUsing: fn($query) => $query
                        ->where('role', UserRole::Student->value)
                )
                ->searchable()
                ->preload()
                ->required(),

             Select::make('qualification_id')
                ->label('Qualification')
                ->relationship(
                    name: 'qualification',
                    titleAttribute: 'qualification_code'
                )
                ->preload()
                ->required(),
                Select::make('scholarship_program')
                    ->options(ScholarshipProgram::class)
                    ->required(),
                Select::make('user_role')
                    ->label('ID Card Type')
                    ->options(UserRole::class)
                    ->required(),
            DatePicker::make('application_date')
                ->label('Application Date')
                ->default(now())
                ->required()
                ->readOnly()
                ->dehydrated(true),
                TextInput::make('reason')
                    ->hidden()
                    ->default('ID Card Application'),
                Select::make('approved_by')
                    ->label('Approved By')
                    ->relationship('approvedBy', 'name')
                    ->hidden(),
                TextInput::make('status')
                    ->hidden()
                    ->default('active'),
                DateTimePicker::make('approved_at')
                    ->hidden()
                    ->default(fn () => now()),
                Textarea::make('remarks')
                    ->columnSpanFull(),
            ]);
    }
}
