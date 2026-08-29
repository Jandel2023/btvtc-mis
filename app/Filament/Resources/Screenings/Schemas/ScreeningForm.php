<?php

namespace App\Filament\Resources\Screenings\Schemas;

use App\Models\Screening;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
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
                    ->maxLength(11),
                Select::make('batch_id')
                    ->relationship('Batch', 'batch_name')
                    ->required()
                    ->live()
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
                        } elseif (\App\Models\Trainee::query()
                            ->where('screening_id', $get('id'))
                            ->where('enroll_status', true)
                            ->exists()) {
                            $set('enrolled_status', true);
                        } else {
                            $set('enrolled_status', false);
                        }
                            
                        
                    }),

                TextInput::make('address'),
                DatePicker::make('date_screened')
                    ->default(now()),
                TextInput::make('remarks'),
                Select::make('screened_by')
                    ->label('Screened By')
                    ->options(fn () => User::query()
                        ->whereKeyNot(Auth::id())
                        ->pluck('name', 'name'))
                    ->searchable()
                    ->preload()
                    ->nullable(),
                 TextInput::make('enrolled_status')
                    ->formatStateUsing(fn (?Bool $state): string => match ($state) {
                        true => 'Enrolled',
                        false => 'Not Enrolled', 
                    })
                    ->live()
                    ->dehydrated(),
            ]);
    }
}
