<?php


namespace App\Filament\Resources\Batches\Schemas;

use App\Enums\ScheduleType;
use App\Enums\ScholarshipProgram;
use App\Models\Batch;
use App\Models\Qualifications;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use App\Enums\Status;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\CheckboxList;
use Carbon\Carbon;

class BatchForm
{
    protected static function calculateEndDate($qualificationId, $startDate, $scheduled): ?string
{
    if (! $qualificationId || ! $startDate || empty($scheduled)) {
        return null;
    }

    $qualification = Qualifications::find($qualificationId);
    $trainingHours = (float) ($qualification?->training_hours ?? 0);

    if ($trainingHours <= 0) {
        return null;
    }

    $scheduledDays = collect((array) $scheduled)
        ->map(fn ($day) => strtolower((string) ($day instanceof ScheduleType ? $day->value : $day)))
        ->all();

    $scheduledCount = count($scheduledDays);

    if ($scheduledCount <= 0) {
        return null;
    }

    $hoursPerDay = 8;
    $weeklyCapacity = $scheduledCount * $hoursPerDay; // total hours covered per week

    // Total training *sessions* needed = training_hours / 8 (same as option A)
    $requiredDays = (int) ceil($trainingHours / $hoursPerDay);

    $date = Carbon::parse($startDate);
    $trainingDays = 0;

    while ($trainingDays < $requiredDays) {
        $dayNames = [
            strtolower($date->format('l')),
            strtolower($date->format('D')),
            (string) $date->dayOfWeek,
        ];

        if (array_intersect($dayNames, $scheduledDays)) {
            $trainingDays++;
        }

        if ($trainingDays < $requiredDays) {
            $date->addDay();
        }
    }

    return $date->toDateString();
}

    protected static function generateBatchCode(
        $qualificationId,
        $scholarshipProgram
    ): ?string {
        if (! $qualificationId || ! $scholarshipProgram) {
            return null;
        }

        $qualification = Qualifications::find($qualificationId);

        if (! $qualification) {
            return null;
        }

        // Convert enum to its actual value
        $scholarshipValue = $scholarshipProgram instanceof ScholarshipProgram
            ? $scholarshipProgram->value
            : $scholarshipProgram;

        $count = Batch::where('qualification_id', $qualificationId)
            ->where('scholarship_program', $scholarshipValue)
            ->count() + 1;

        return $qualification->qualification_code
            . '-'
            . $scholarshipValue
            . '-'
            . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

            Select::make('ntp_id')
                ->label('RQM CODE')
                ->nullable()
                ->default(null)
                ->disabledOn('edit')

                ->relationship(
                    'ntp',
                    'rqm_code',
                    modifyQueryUsing: function ($query, Get $get) {
                        $currentNtpId = $get('ntp_id');

                        $query->where(function ($query) use ($currentNtpId) {
                            $query->whereNotIn(
                                'id',
                                \App\Models\Batch::query()
                                    ->whereNotNull('ntp_id')
                                    ->pluck('ntp_id')
                            );

                            if ($currentNtpId) {
                                $query->orWhere('id', $currentNtpId);
                            }
                        });
                    }
                )
                
                ->live()
                ->afterStateUpdated(function ($state, Set $set, Get $get) {

                    if (! $state) {
                        $set('qualification_id', null);
                        $set('scholarship_program', null);
                        $set('batch_code', null);

                        return;
                    }

                    $ntp = \App\Models\Ntp::find($state);

                    if ($ntp) {
                        // Automatically select the qualification
                        $set('qualification_id', $ntp->qualification_id);
                        // Automatically select the scholarship program
                        $set('scholarship_program', $ntp->scholarship_program);
                        // Generate batch code
                        $set(
                            'batch_code',
                            self::generateBatchCode(
                                $ntp->qualification_id,
                                $get('scholarship_program')
                            )
                        );
                    }
                }),

            TextInput::make('batch_name')
                ->required(),

            Select::make('qualification_id')
                ->relationship(
                    'qualification',
                    'qualification_code',
                    fn($query) => $query->where('is_active', true),
                )
                ->disabledOn('edit')
                ->required()
                ->live()
                ->dehydrated()
                ->afterStateUpdated(function (
                    $state,
                    Set $set,
                    Get $get
                ) {
                    $set(
                        'batch_code',
                        self::generateBatchCode(
                            $state,
                            $get('scholarship_program')
                        ),
                        'end_date', static::calculateEndDate(
                            $get('qualification_id'),
                            $get('start_date'),
                            $get('schedule'),
                   
                    ));
                }),

            Select::make('scholarship_program')
                ->label('Scholarship Program')
                ->options(ScholarshipProgram::class)
                ->disabledOn('edit')
                ->required()
                ->live()
                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                    $set(
                        'batch_code',
                        self::generateBatchCode(
                            $get('qualification_id'),
                            $state
                        )
                    );
                    $set('end_date', self::calculateEndDate(
                        $get('qualification_id'),
                        $get('start_date'),
                        $get('schedule')
                    ));
                }),
                TextInput::make('batch_code')
                    ->hidden()
                    ->dehydrated(),

          CheckboxList::make('schedule')
                    ->label('Is Scheduled')
                    ->options(ScheduleType::class)
                    ->columns(3)
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $set('end_date', static::calculateEndDate(
                            $get('qualification_id'),
                            $get('start_date'),
                            $get('schedule'),
                        ));
                    }),

                DatePicker::make('start_date')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $set('end_date', static::calculateEndDate(
                            $get('qualification_id'),
                            $get('start_date'),
                            $get('schedule'),
                        ));

                        if ($state && Carbon::parse($state)->isBefore(Carbon::today())) {
                            $set('status', Status::Ongoing->value);
                        } else {
                            $set('status', Status::Upcoming->value);
                        }
                    }),

                
                DatePicker::make('end_date')
                   ->label('End date')
                ->native(false)
                ->disabled() // auto-calculated, prevent manual edits
                ->dehydrated(), // still saves the value even though disabled
              
                TextInput::make('venue')
                    ->label('Training Venue')
                    ->default('Baybay Technical Vocational Training Center'),
                Select::make('status')
                    ->options(Status::class)
                    ->required(),
                Textarea::make('remarks')
                    ->default('No remarks')
                    ->columnSpanFull(),
            ]);
    }
}
