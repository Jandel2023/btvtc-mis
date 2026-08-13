<?php

namespace App\Filament\Resources\ReleaseToolkits\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use App\Enums\ScholarshipProgram;
use Filament\Forms\Components\asSelectArray;


class ReleaseToolkitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                    ->required(),
                TextInput::make('middle_name'),
                TextInput::make('last_name')
                    ->required(),
                Select::make('qualification_id')
                    ->relationship('qualifications', 'qualification_code')
                    ->required(),
                TextInput::make('contact_number'),
                Select::make('scholarship_program')
                    ->label('Scholarship Program')
                    ->options(ScholarshipProgram::class)
                    ->required(),
                DatePicker::make('date_recieved'),
            ]);
    }
}
