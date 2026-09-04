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
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
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
            ->schema([
                Wizard::make([
                    /*
                    |--------------------------------------------------------------------------
                    | STEP 1: TRAINEE INFORMATION
                    |--------------------------------------------------------------------------
                    */
                    Step::make('Trainee Information')
                        ->icon('heroicon-o-user')
                        ->description('Enter the trainee\'s personal information')
                        ->schema([
                            Section::make('Personal Information')
                                ->description('Provide the trainee\'s complete name and contact details.')
                                ->schema([
                                    TextInput::make('fname')
                                        ->label('First Name')
                                        ->required()
                                        ->maxLength(255)
                                        ->dehydrateStateUsing(
                                            fn (?string $state): ?string =>
                                                $state === null ? null : Str::upper($state)
                                        ),

                                    TextInput::make('mname')
                                        ->label('Middle Name')
                                        ->maxLength(255)
                                        ->dehydrateStateUsing(
                                            fn (?string $state): ?string =>
                                                $state === null ? null : Str::upper($state)
                                        ),

                                    TextInput::make('lname')
                                        ->label('Last Name')
                                        ->required()
                                        ->maxLength(255)
                                        ->dehydrateStateUsing(
                                            fn (?string $state): ?string =>
                                                $state === null ? null : Str::upper($state)
                                        )
                                        ->rules([
                                            fn (Get $get, ?Screening $record): Closure => function (
                                                string $attribute,
                                                mixed $value,
                                                Closure $fail
                                            ) use ($get, $record): void {
                                                $batchId = $get('batch_id');
                                                $firstName = $get('fname');
                                                $middleName = $get('mname');

                                                if (!$batchId || !$firstName || !$value) {
                                                    return;
                                                }

                                                $duplicateScreeningExists = Screening::query()
                                                    ->where('batch_id', $batchId)
                                                    ->whereRaw(
                                                        'UPPER(fname) = ?',
                                                        [Str::upper($firstName)]
                                                    )
                                                    ->whereRaw(
                                                        'UPPER(lname) = ?',
                                                        [Str::upper($value)]
                                                    )
                                                    ->when(
                                                        filled($middleName),
                                                        fn ($query) => $query->whereRaw(
                                                            'UPPER(mname) = ?',
                                                            [Str::upper($middleName)]
                                                        ),
                                                        fn ($query) => $query->where(
                                                            fn ($query) => $query
                                                                ->whereNull('mname')
                                                                ->orWhere('mname', '')
                                                        )
                                                    )
                                                    ->when(
                                                        $record,
                                                        fn ($query) => $query->whereKeyNot(
                                                            $record->getKey()
                                                        )
                                                    )
                                                    ->exists();

                                                if ($duplicateScreeningExists) {
                                                    $fail(
                                                        'A trainee with this full name is already registered in the selected batch.'
                                                    );
                                                }
                                            },
                                        ]),

                                    TextInput::make('phone')
                                        ->label('Phone Number')
                                        ->tel()
                                        ->prefix('+63')
                                        ->numeric()
                                        ->maxLength(11),

                                    TextInput::make('address')
                                        ->label('Address')
                                        ->required()
                                        ->maxLength(500),
                                ])
                                ->columns(2),

                            Section::make('Trainee Picture')
                                ->description('Upload a clear photo of the trainee.')
                                ->schema([
                                    FileUpload::make('picture')
                                        ->label('Trainee Picture')
                                        ->image()
                                        ->acceptedFileTypes([
                                            'image/jpeg',
                                            'image/png',
                                            'image/webp',
                                        ])
                                        ->directory('trainees_pictures')
                                        ->disk('public')
                                        ->visibility('public')
                                        ->imageEditor()
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    /*
                    |--------------------------------------------------------------------------
                    | STEP 2: SCREENING SCORES
                    |--------------------------------------------------------------------------
                    */
                    Step::make('Screening Scores')
                        ->icon('heroicon-o-clipboard-document-check')
                        ->description('Enter and review the screening scores')
                        ->schema([
                            Section::make('Screening Results')
                                ->description('Enter the aptitude and interview scores. The total score is calculated automatically.')
                                ->schema([
                                    TextInput::make('aptitude_score')
                                        ->label('Aptitude Score')
                                        ->numeric()
                                        ->minValue(0)
                                        ->maxValue(30)
                                        ->required()
                                        ->live()
                                        ->suffix('/ 30')
                                        ->afterStateUpdated(
                                            function ($state, Set $set, Get $get) {
                                                self::calculateScore($set, $get);
                                            }
                                        ),

                                    TextInput::make('interview_score')
                                        ->label('Interview Score')
                                        ->numeric()
                                        ->minValue(0)
                                        ->maxValue(100)
                                        ->required()
                                        ->live()
                                        ->suffix('/ 100')
                                        ->afterStateUpdated(
                                            function ($state, Set $set, Get $get) {
                                                self::calculateScore($set, $get);
                                            }
                                        ),

                                    TextInput::make('total_score')
                                        ->label('Total Score')
                                        ->numeric()
                                        ->readOnly()
                                        ->dehydrated()
                                        ->step(0.01)
                                        ->suffix('/ 100'),

                                    Select::make('status')
                                        ->label('Screening Status')
                                        ->options([
                                            'Passed' => 'Passed',
                                            'Failed' => 'Failed',
                                        ])
                                        ->required()
                                        ->disabled()
                                        ->dehydrated(),
                                ])
                                ->columns(2),
                        ]),

                    /*
                    |--------------------------------------------------------------------------
                    | STEP 3: BATCH & ENROLLMENT
                    |--------------------------------------------------------------------------
                    */
                    Step::make('Batch & Enrollment')
                        ->icon('heroicon-o-academic-cap')
                        ->description('Assign the trainee to a batch and manage enrollment')
                        ->schema([
                            Section::make('Batch Assignment')
                                ->description('Select the batch where the trainee will be registered.')
                                ->schema([
                                    Select::make('batch_id')
                                        ->label('Batch')
                                        ->relationship(
                                            'Batch',
                                            'batch_name',
                                            modifyQueryUsing: function (
                                                Builder $query,
                                                Select $component
                                            ): void {
                                                $selectedBatchId = $component->getState();

                                                $query->where(function (
                                                    Builder $query
                                                ) use ($selectedBatchId): void {
                                                    $query->whereHas(
                                                        'ntp',
                                                        function (Builder $query): void {
                                                            $query->whereRaw(
                                                                '(SELECT COUNT(*)
                                                                FROM screenings
                                                                INNER JOIN batches AS enrolled_batches
                                                                    ON enrolled_batches.id = screenings.batch_id
                                                                WHERE screenings.enrolled_status = 1
                                                                    AND enrolled_batches.ntp_id = ntps.id)
                                                                < ntps.approve_slots'
                                                            );
                                                        }
                                                    );

                                                    if (filled($selectedBatchId)) {
                                                        $query->orWhere(
                                                            $query->getModel()->getKeyName(),
                                                            $selectedBatchId
                                                        );
                                                    }
                                                });
                                            }
                                        )
                                        ->required()
                                        ->searchable()
                                        ->preload()
                                        ->live()
                                        ->afterStateUpdated(
                                            function ($state, Set $set) {
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
                                                    ->whereHas(
                                                        'batch',
                                                        function ($query) use ($batchNtpId) {
                                                            $query->where(
                                                                'ntp_id',
                                                                $batchNtpId
                                                            );
                                                        }
                                                    )
                                                    ->count();

                                                if (
                                                    $approvedCount >=
                                                    (int) $batch->ntp->approve_slots
                                                ) {
                                                    $set('enrolled_status', false);

                                                    Notification::make()
                                                        ->warning()
                                                        ->title('Batch capacity reached')
                                                        ->body(
                                                            'This NTP has reached its approved slot limit. The screening may still be saved, but the batch is already full for enrollment.'
                                                        )
                                                        ->send();
                                                }
                                            }
                                        ),

                                    DatePicker::make('date_screened')
                                        ->label('Screening Date')
                                        ->default(now())
                                        ->readOnly(),

                                    Select::make('screened_by')
                                        ->label('Screened By')
                                        ->options(
                                            fn () => User::query()
                                                ->whereKeyNot(Auth::id())
                                                ->pluck('name', 'name')
                                        )
                                        ->searchable()
                                        ->preload()
                                        ->nullable(),
                                ])
                                ->columns(2),

                            Section::make('Enrollment')
                                ->description('Only trainees who passed the screening can be enrolled.')
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
                                        ->disabled(
                                            fn (Get $get): bool =>
                                                $get('status') !== 'Passed'
                                        )
                                        ->dehydrateStateUsing(
                                            function ($state, Get $get) {
                                                return $get('status') === 'Passed'
                                                    ? $state
                                                    : false;
                                            }
                                        )
                                        ->afterStateUpdated(
                                            function (
                                                $state,
                                                Set $set,
                                                Get $get,
                                                ?Screening $record
                                            ) {
                                                self::handleEnrollment(
                                                    $state,
                                                    $set,
                                                    $get,
                                                    $record
                                                );
                                            }
                                        ),
                                ]),
                        ]),

                    /*
                    |--------------------------------------------------------------------------
                    | STEP 4: ADDITIONAL INFORMATION
                    |--------------------------------------------------------------------------
                    */
                    Step::make('Additional Information')
                        ->icon('heroicon-o-document-text')
                        ->description('Add remarks or additional notes')
                        ->schema([
                            Section::make('Remarks')
                                ->description('Add any additional information about the trainee or screening.')
                                ->schema([
                                    Textarea::make('remarks')
                                        ->label('Remarks')
                                        ->rows(6)
                                        ->placeholder(
                                            'Enter any additional notes or remarks...'
                                        )
                                        ->columnSpanFull(),
                                ]),

                            Section::make('Record Information')
                                ->schema([
                                    DatePicker::make('created_at')
                                        ->label('Created At')
                                        ->default(now())
                                        ->readOnly()
                                        ->visible(
                                            fn (): bool =>
                                                $record?->exists ?? false
                                        ),
                                ]),
                        ]),
                ])
                ->columnSpanFull()
                ->persistStepInQueryString()
                // ->submitAction(
                //     view('filament.forms.components.screening-submit-button')
                // ),
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SCORE CALCULATION
    |--------------------------------------------------------------------------
    */
    protected static function calculateScore(
        Set $set,
        Get $get
    ): void {
        $aptitude = (float) ($get('aptitude_score') ?? 0);
        $interview = (float) ($get('interview_score') ?? 0);

        $total = $aptitude + ($interview * 0.70);

        $set('total_score', round($total, 2));
        $set('status', $total >= 75 ? 'Passed' : 'Failed');
    }

    /*
    |--------------------------------------------------------------------------
    | ENROLLMENT HANDLER
    |--------------------------------------------------------------------------
    */
    protected static function handleEnrollment(
        $state,
        Set $set,
        Get $get,
        ?Screening $record
    ): void {
        // Only process enrollment when editing an existing record.
        if (!($record?->exists ?? false)) {
            return;
        }

        // Only allow enrollment, not un-enrollment.
        if ($state !== true || $record->enrolled_status) {
            return;
        }

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
            ->whereHas(
                'batch',
                function ($query) use ($batch) {
                    $query->where('ntp_id', $batch->ntp_id);
                }
            )
            ->count();

        if (
            $enrolledCount >=
            (int) $batch->ntp->approve_slots
        ) {
            Notification::make()
                ->warning()
                ->title('Batch Capacity Reached')
                ->body(
                    "This NTP has reached its approved slot limit ({$batch->ntp->approve_slots})."
                )
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
