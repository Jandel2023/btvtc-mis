<?php

namespace App\Filament\Resources\Screenings\Schemas;

use App\Models\Batch;
use App\Models\Screening;
use App\Models\User;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ScreeningForm
{
    public static function configure(Schema $schema, ?Screening $record = null): Schema
    {
        return $schema
            ->components([
                Section::make('Personal Information')
                        ->description('Candidate details')
                        ->columns(3)
                        ->schema([
                            TextInput::make('fname')
                                ->label('First Name')
                                ->required()
                                ->dehydrateStateUsing(
                                    fn(?string $state): ?string => $state === null ? null : Str::upper($state)
                                )
                                ->columnSpan(1),
                            TextInput::make('mname')
                                ->label('Middle Name')
                                ->dehydrateStateUsing(
                                    fn(?string $state): ?string => $state === null ? null : Str::upper($state)
                                )
                                ->columnSpan(1),
                            TextInput::make('lname')
                                ->label('Last Name')
                                ->required()
                                ->dehydrateStateUsing(
                                    fn(?string $state): ?string => $state === null ? null : Str::upper($state)
                                )
                                ->rules([
                                    fn(Get $get, ?Screening $record): Closure => function (string $attribute, mixed $value, Closure $fail, ) use ($get, $record): void {
                                        $batchId = $get('batch_id');
                                        $firstName = $get('fname');
                                        $middleName = $get('mname');

                                        if (!$batchId || !$firstName || !$value) {
                                            return;
                                        }

                                        $duplicateScreeningExists = Screening::query()
                                            ->where('batch_id', $batchId)
                                            ->whereRaw('UPPER(fname) = ?', [Str::upper($firstName)])
                                            ->whereRaw('UPPER(lname) = ?', [Str::upper($value)])
                                            ->when(
                                                filled($middleName),
                                                fn($query) => $query->whereRaw(
                                                    'UPPER(mname) = ?',
                                                    [Str::upper($middleName)]
                                                ),
                                                fn($query) => $query->where(
                                                    fn($query) => $query
                                                        ->whereNull('mname')
                                                        ->orWhere('mname', '')
                                                ),
                                            )
                                            ->when(
                                                $record,
                                                fn($query) => $query->whereKeyNot($record->getKey()),
                                            )
                                            ->exists();

                                        if ($duplicateScreeningExists) {
                                            $fail('A trainee with this full name is already registered in the selected batch.');
                                        }

                                    },
                                ])
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
                                ->columnSpan([
                                    'default' => 1,
                                    'md' => 4,
                                    'xl' => 6,
                                ]),
                        ]),
                Section::make('Screening Assessment')
                    ->description('Aptitude and interview scores')
                    ->columns(4)
                    ->schema([
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
                            ->columnSpanFull(),
                    ]),


                Section::make('Batch & Scheduling')
                    ->description('Batch assignment and screening dates')
                    ->columns(4)
                    ->schema([
                        Select::make('batch_id')
                            ->label('Batch')
                            ->relationship(
                                'Batch',
                                'batch_name',
                                modifyQueryUsing: function (Builder $query, Select $component): void {
                                    $selectedBatchId = $component->getState();

                                    $query->where(function (Builder $query) use ($selectedBatchId): void {
                                        $query->whereHas('ntp', function (Builder $query): void {
                                            $query->whereRaw(
                                                '(SELECT COUNT(*) FROM screenings INNER JOIN batches AS enrolled_batches ON enrolled_batches.id = screenings.batch_id WHERE screenings.enrolled_status = 1 AND enrolled_batches.ntp_id = ntps.id) < ntps.approve_slots'
                                            );
                                        });

                                        if (filled($selectedBatchId)) {
                                            $query->orWhere(
                                                $query->getModel()->getKeyName(),
                                                $selectedBatchId,
                                            );
                                        }
                                    });
                                }
                            )
                            ->required()
                            ->live()
                            ->columnSpan(1)
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $set('enrolled_status', false);

                                if (!$state) {
                                    return;
                                }

                                $batch = Batch::with('ntp')->find($state);

                                if (!$batch || !$batch->ntp) {
                                    return;
                                }

                                $batchNtpId = $batch->getAttribute('ntp_id');

                                $approvedCount = Screening::query()
                                    ->where('enrolled_status', true)
                                    ->whereHas('batch', function ($query) use ($batchNtpId) {
                                        $query->where('ntp_id', $batchNtpId);
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
                            ->options(fn() => User::query()
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
                            ->visible(fn(): bool => $record?->exists ?? false),
                    ]),


                Section::make('Enrollment Status')
                    ->description('Enrollment status')
                    ->columns(1)
                    ->schema([
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
                            ->disabled(function (Get $get): bool {
                                return $get('status') !== 'Passed';
                            })
                            ->dehydrateStateUsing(function ($state, Get $get) {
                                return $get('status') === 'Passed'
                                    ? $state
                                    : false;
                            })
                            ->afterStateUpdated(function ($state, Set $set, Get $get, ?Screening $record) {
                                // Only allow enrollment (true), not un-enrollment
                                if ($record?->exists ?? false) {
                                    if ($state === true && !$record?->enrolled_status) {
                                        $batchId = $get('batch_id');

                                        if (!$batchId) {
                                            Notification::make()
                                                ->danger()
                                                ->title('Error')
                                                ->body('Please select a batch first.')
                                                ->send();
                                            $set('enrolled_status', false);

                                            return;
                                        }

                                        $batch = Batch::with('ntp')->find($batchId);

                                        if (!$batch || !$batch->ntp) {
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
                                }

                            }),
                    ]),
                Section::make('Additional Information')
    ->description('Notes and comments regarding the screening')
                    ->columns(2)
    ->schema([
    FileUpload::make('picture')
                ->label('Trainee Picture')
                ->image()
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                ->directory('trainees_pictures')
                ->disk('public')
                ->visibility('public')
                ->columnSpanFull(),

        Textarea::make('remarks')
            ->label('Remarks')
            ->rows(4)
            ->columnSpanFull(),
    ])->columnSpanFull(),

                        ]);
    }
}
