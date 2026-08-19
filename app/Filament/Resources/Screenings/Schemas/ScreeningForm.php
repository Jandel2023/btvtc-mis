<?php

namespace App\Filament\Resources\Screenings\Schemas;

use App\Enums\ScholarshipProgram;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class ScreeningForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('fname')
                    ->label('First Name')
                    ->required(),
                TextInput::make('lname')
                    ->label('Last Name')
                    ->required(),
                TextInput::make('mname')
                    ->label('Middle Name'),
            TextInput::make('aptitude_score')
                ->label('Aptitude Score')
                ->required()
                ->numeric()
                ->live()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $aptitude = (float) ($state ?? 0);
                    $interview = (float) ($get('interview_score') ?? 0);

                $set(
                    'total_score',
                    (($aptitude / 30) * 30) +
                        (($interview / 100) * 70)
                );
                }),

            TextInput::make('interview_score')
                ->label('Interview Score')
                ->required()
                ->numeric()
                ->live()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $aptitude = (float) ($get('aptitude_score') ?? 0);
                    $interview = (float) ($state ?? 0);

                        $set(
                            'total_score',
                            (($aptitude / 30) * 30) +
                                (($interview / 100) * 70)
                        );
                }),

            TextInput::make('total_score')
                ->label('Total Score')
                ->numeric()
                ->readOnly(),
                TextInput::make('phone')
                    ->tel(),
                Select::make('qualification_id')
                    ->relationship('qualification', 'qualification_code')
                    ->required(),
                Select::make('scholarship_program')
                    ->label('Scholarship Program')
                    ->options(ScholarshipProgram::class)
                    ->required(),
                TextInput::make('address'),
                DatePicker::make('date_screened')
                    ->default(now())
                    ->required(),
                TextInput::make('remarks')
                    ->label('Remarks')
                    ->maxLength(255)
                    ->nullable()
                    ->helperText('Optional')
                    ->columnSpanFull()
                    ->placeholder('Enter remarks here...'),
                TextInput::make('screened_by')
                    ->default("Admin"),
            ]);
    }
}
