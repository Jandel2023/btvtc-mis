<?php

namespace App\Filament\Exports;

use App\Models\ReleaseToolkit;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Str;

class ReleaseToolkitExporter extends Exporter
{
    protected static ?string $model = ReleaseToolkit::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('batch.batch_name')
                ->label('Batch'),
            ExportColumn::make('screening.full_name')
                ->label('Applicant'),
            ExportColumn::make('date_recieved')
                ->label('Date Received'),
            ExportColumn::make('Notes')
                ->label('Notes'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Release toolkit export completed successfully. '
            .Str::of('row')->counted($export->successful_rows)
            .' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '
                .Str::of('row')->counted($failedRowsCount)
                .' failed to export.';
        }

        return $body;
    }
}
