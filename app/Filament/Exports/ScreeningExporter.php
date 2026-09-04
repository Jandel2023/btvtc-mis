<?php

namespace App\Filament\Exports;

use App\Models\Screening;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Str;

class ScreeningExporter extends Exporter
{
    protected static ?string $model = Screening::class;

    public static function getColumns(): array
    {
        return [

            // =====================================================
            // APPLICANT INFORMATION
            // =====================================================

            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('fname')
                ->label('First Name'),

            ExportColumn::make('mname')
                ->label('Middle Name'),

            ExportColumn::make('lname')
                ->label('Last Name'),

            ExportColumn::make('phone')
                ->label('Contact Number'),

            ExportColumn::make('address')
                ->label('Address'),


            // =====================================================
            // SCREENING RESULTS
            // =====================================================

            ExportColumn::make('aptitude_score')
                ->label('Aptitude Score'),

            ExportColumn::make('interview_score')
                ->label('Interview Score'),

            ExportColumn::make('total_score')
                ->label('Total Score'),

            ExportColumn::make('status')
                ->label('Screening Status'),
            ExportColumn::make('enrolled_status')
                ->label('Enrollment Status')
                ->formatStateUsing(fn ($state): string => $state
                    ? 'Enrolled'
                    : 'Not Enrolled'
                ),


            // =====================================================
            // TRAINING / BATCH INFORMATION
            // =====================================================

            ExportColumn::make('batch.batch_name')
                ->label('Batch'),


            // =====================================================
            // SCREENING DETAILS
            // =====================================================

            ExportColumn::make('date_screened')
                ->label('Date Screened'),

            ExportColumn::make('screened_by')
                ->label('Screened By'),

            ExportColumn::make('remarks')
                ->label('Remarks'),


            // =====================================================
            // SYSTEM INFORMATION
            // =====================================================

            ExportColumn::make('created_at')
                ->label('Date Created'),

            ExportColumn::make('updated_at')
                ->label('Last Updated'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Screening export completed successfully. '
            . Str::of('row')->counted($export->successful_rows)
            . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '
                . Str::of('row')->counted($failedRowsCount)
                . ' failed to export.';
        }

        return $body;
    }
}

