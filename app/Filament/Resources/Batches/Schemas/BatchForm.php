<?php


namespace App\Filament\Resources\Batches\Schemas;

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


class BatchForm
{
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

                ->relationship(
                    'ntp',
                    'rqm_code',
                    modifyQueryUsing: function ($query) {
                        $query->whereNotIn(
                            'id',
                            \App\Models\Batch::query()
                                ->whereNotNull('ntp_id')
                                ->pluck('ntp_id')
                        );
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
                        )
                    );
                }),

            Select::make('scholarship_program')
                ->label('Scholarship Program')
                ->options(ScholarshipProgram::class)
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
                }),
                TextInput::make('batch_code')
                    ->hidden()
                    ->dehydrated(),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date'),
                TextInput::make('schedule'),
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
