<?php

namespace App\Filament\Resources\Screenings\Schemas;

use App\Filament\Resources\Screenings\Pages\EditScreening;
use App\Models\Screening;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Actions\Action;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;


class ScreeningForm
{
    public static function configure(Schema $schema, ?Screening $record = null): Schema
    {
        return $schema
            ->components([
                Section::make('Personal Information')
                    ->description('Candidate details')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('fname')
                                ->label('First Name')
                                ->required()
                                ->columnSpan(1),
                            TextInput::make('mname')
                                ->label('Middle Name')
                                ->columnSpan(1),
                            TextInput::make('lname')
                                ->label('Last Name')
                                ->required()
                                ->columnSpan(1),
                            TextInput::make('phone')
                                ->label('Phone Number')
                                ->tel()
                                ->prefix('+63')
                                ->numeric()
                                ->maxLength(11)
                                ->columnSpan(1),
                            TextInput::make('address')
                                ->label('Address')
                                ->required()
                                ->columnSpan(2),
                        ]),
                    ]),

                Section::make('Screening Assessment')
                    ->description('Aptitude and interview scores')
                    ->schema([
                        Grid::make(4)->schema([
                            TextInput::make('aptitude_score')
                                ->label('Aptitude Score')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(30)
                                ->required()
                                ->live()
                                ->columnSpan(1)
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    $aptitude = (float) ($get('aptitude_score') ?? 0);
                                    $interview = (float) ($get('interview_score') ?? 0);
                                    $total = $aptitude + ($interview * 0.70);
                                    $set('total_score', round($total, 2));
                                    $set('status', $total >= 75 ? 'Passed' : 'Failed');
                                }),
                            TextInput::make('interview_score')
                                ->label('Interview Score')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->required()
                                ->live()
                                ->columnSpan(1)
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    $aptitude = (float) ($get('aptitude_score') ?? 0);
                                    $interview = (float) ($get('interview_score') ?? 0);
                                    $total = $aptitude + ($interview * 0.70);
                                    $set('total_score', round($total, 2));
                                    $set('status', $total >= 75 ? 'Passed' : 'Failed');
                                }),
                            TextInput::make('total_score')
                                ->label('Total Score')
                                ->numeric()
                                ->readOnly()
                                ->dehydrated()
                                ->step(0.01)
                                ->columnSpan(1),
                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'Passed' => 'Passed',
                                    'Failed' => 'Failed',
                                ])
                                ->required()
                                ->disabled()
                                ->dehydrated()
                                ->columnSpan(1),
                        ]),
                    ]),

                Section::make('Batch & Scheduling')
                    ->description('Batch assignment and screening dates')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('batch_id')
                                ->label('Batch')
                                ->relationship('Batch', 'batch_name')
                                ->required()
                                ->live()
                                ->columnSpan(1)
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    if (! $state) {
                                        return;
                                    }

                                    $batch = \App\Models\Batch::with('ntp')->find($state);

                                    if (! $batch || ! $batch->ntp) {
                                        return;
                                    }

                                    $approvedCount = Screening::query()
                                        ->where('enrolled_status', true)
                                        ->whereHas('batch', function ($query) use ($batch) {
                                            $query->where('ntp_id', $batch->ntp_id);
                                        })
                                        ->count();

                                    if ($approvedCount >= (int) $batch->ntp->approve_slots) {
                                        $set('enrolled_status', false);

                                        Notification::make()
                                            ->warning()
                                            ->title('Batch capacity reached')
                                            ->body('This NTP has reached its approved slot limit. The screening may still be saved, but the batch is already full for enrollment.')
                                            ->send();
                                    }
                                }),
                            DatePicker::make('date_screened')
                                ->label('Screening Date')
                                ->default(now())
                                ->readOnly()
                                ->columnSpan(1),
                            Select::make('screened_by')
                                ->label('Screened By')
                                ->options(fn () => User::query()
                                    ->whereKeyNot(Auth::id())
                                    ->pluck('name', 'name'))
                                ->searchable()
                                ->preload()
                                ->nullable()
                                ->columnSpan(1),
                            DatePicker::make('created_at')
                                ->label('Created At')
                                ->default(now())
                                ->readOnly()
                                ->columnSpan(1)
                                ->visible(fn (): bool => $record?->exists ?? false),
                        ]),
                    ]),

                Section::make('Additional Information')
                    ->description('Notes and enrollment status')
                    ->schema([
                        TextInput::make('remarks')
                            ->label('Remarks')
                            ->columnSpanFull(),
                        ToggleButtons::make('enrolled_status')
                            ->label('Enrollment Status')
                            ->options([
                                true => 'Enrolled',
                                false => 'Not Enrolled',
                            ])
                            ->boolean()
                            ->live()
                            ->inline()
                            ->dehydrated()
                            ->columnSpanFull()
                            ->afterStateUpdated(function ($state, Set $set, Get $get, ?Screening $record) {
                                // Only allow enrollment (true), not un-enrollment
                                if ($state === true && ! $record?->enrolled_status) {
                                    $batchId = $get('batch_id');
                                    if (! $batchId) {
                                        Notification::make()
                                            ->danger()
                                            ->title('Error')
                                            ->body('Please select a batch first.')
                                            ->send();
                                        $set('enrolled_status', false);

                                        return;
                                    }

                                    $batch = \App\Models\Batch::with('ntp')->find($batchId);

                                    if (! $batch || ! $batch->ntp) {
                                        Notification::make()
                                            ->danger()
                                            ->title('Error')
                                            ->body('Batch or NTP information is missing.')
                                            ->send();
                                        $set('enrolled_status', false);

                                        return;
                                    }

                                    $enrolledCount = Screening::query()
                                        ->where('enrolled_status', true)
                                        ->whereHas('batch', function ($query) use ($batch) {
                                            $query->where('ntp_id', $batch->ntp_id);
                                        })
                                        ->count();

                                    if ($enrolledCount >= (int) $batch->ntp->approve_slots) {
                                        Notification::make()
                                            ->warning()
                                            ->title('Batch Capacity Reached')
                                            ->body("This NTP has reached its approved slot limit ({$batch->ntp->approve_slots}).")
                                            ->send();
                                        $set('enrolled_status', false);

                                        return;
                                    }

                                    Notification::make()
                                        ->success()
                                        ->title('Success')
                                        ->body('Trainee enrolled successfully.')
                                        ->send();
                                }
                            }),
                    ]),
            ]);
    }
}
