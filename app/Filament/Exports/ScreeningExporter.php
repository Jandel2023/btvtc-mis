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
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('fname'),
            ExportColumn::make('lname'),
            ExportColumn::make('mname'),
            ExportColumn::make('aptitude_score'),
            ExportColumn::make('interview_score'),
            ExportColumn::make('total_score'),
            ExportColumn::make('phone'),
            ExportColumn::make('batch.batch_name'),
            ExportColumn::make('status'),
            ExportColumn::make('address'),
            ExportColumn::make('date_screened'),
            ExportColumn::make('remarks'),
            ExportColumn::make('screened_by'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your screening export has completed and ' . Str::of('row')->counted($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Str::of('row')->counted($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }



}
