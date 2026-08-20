<?php

namespace App\Filament\Resources\Screenings\Schemas;

use App\Enums\ScholarshipProgram;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

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
                    ->afterStateUpdated(function (Set $set, ?string $state, Get $get): void {
                        self::updateScoreAndStatus($set, $state, $get('interview_score'));
                    }),
                TextInput::make('interview_score')
                    ->label('Interview Score')
                    ->required()
                    ->numeric()
                    ->live()
                    ->afterStateUpdated(function (Set $set, ?string $state, Get $get): void {
                        self::updateScoreAndStatus($set, $get('aptitude_score'), $state);
                    }),
                TextInput::make('total_score')
                    ->label('Total Score')
                    ->numeric()
                    ->readOnly(),
                Hidden::make('status'),
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
                    ->default('Admin'),
            ]);
    }

    private static function updateScoreAndStatus(Set $set, mixed $aptitudeScore, mixed $interviewScore): void
    {
        $totalScore = (float) ($aptitudeScore ?? 0) + ((float) ($interviewScore ?? 0) / 100 * 70);

        $set('total_score', $totalScore);
        $set('status', $totalScore < 75 ? 'Failed' : 'Passed');
    }
}
