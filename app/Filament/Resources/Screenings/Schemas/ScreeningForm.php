<?php

namespace App\Filament\Resources\Screenings\Schemas;

use App\Enums\Status;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Facades\Auth;


class ScreeningForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('fname')
                    ->label('Firstname:')
                    ->required(),
                TextInput::make('lname')
                    ->label('Lastname:')
                    ->required(),
                TextInput::make('mname')
                    ->label('Middlename:'),
            TextInput::make('aptitude_score')
                ->label('Aptitude Score:')
                ->numeric()
                ->minValue(0)
                ->maxValue(30)
                ->required()
                ->live()
                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                    $aptitude = (float) ($get('aptitude_score') ?? 0);
                    $interview = (float) ($get('interview_score') ?? 0);

                    $total = $aptitude + ($interview * 0.70);

                    $set('total_score', round($total, 2));
                    $set('status', $total >= 75 ? 'Passed' : 'Failed');
                }),

            TextInput::make('interview_score')
                ->label('Interview Score:')
                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->required()
                ->live()
                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                    $aptitude = (float) ($get('aptitude_score') ?? 0);
                    $interview = (float) ($get('interview_score') ?? 0);

                    $total = $aptitude + ($interview * 0.70);

                    $set('total_score', round($total, 2));
                    $set('status', $total >= 75 ? 'Passed' : 'Failed');
                }),

                TextInput::make('total_score')
                    ->label('Total Score:')
                    ->numeric()
                    ->readOnly()
                    ->dehydrated()
                    ->step(0.01),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Passed' => 'Passed',
                        'Failed' => 'Failed',
                    ])
                    ->required()
                    ->disabled()
                    ->dehydrated(),
                TextInput::make('phone')
                    ->tel()
                    ->prefix('+63')
                    ->numeric()
                    ->maxLength(10),
                Select::make('batch_id')
                    ->relationship('Batch','batch_name')
                    ->required(),

        
                TextInput::make('address'),
                DatePicker::make('date_screened')
                    ->default(now()),
                TextInput::make('remarks'),
               Select::make('screened_by')
                    ->label('Screened By')
                    ->default(fn () => Auth::user()?->name)
                    ->options(
                        \App\Models\User::pluck('name', 'name')
                    )
                    ->searchable()
                    ->preload(),
                            ]);
                    }
}
