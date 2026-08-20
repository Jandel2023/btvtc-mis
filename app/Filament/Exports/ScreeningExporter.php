<?php

namespace App\Filament\Exports;

use App\Models\Screening;
use Filament\Actions\Exports\Enums\ExportFormat;
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
            ExportColumn::make('full_name')
                ->label('Full Name')
                ->state(fn(Screening $record): string => $record->full_name),

            ExportColumn::make('qualification.qualification_code')
                ->label('Qualification'),

            ExportColumn::make('aptitude_score')
                ->label('Aptitude Score'),

            ExportColumn::make('interview_score')
                ->label('Interview Score'),

            ExportColumn::make('total_score')
                ->label('Total Score'),

            ExportColumn::make('status')
                ->label('Status'),

            ExportColumn::make('phone')
                ->label('Phone'),

            ExportColumn::make('scholarship_program')
                ->label('Scholarship Program'),

            ExportColumn::make('address')
                ->label('Address'),

            ExportColumn::make('date_screened')
                ->label('Date Screened'),

            ExportColumn::make('remarks')
                ->label('Remarks'),

            ExportColumn::make('screened_by')
                ->label('Screened By'),
        ];
    }

    public static function getCompletedNotificationTitle(
        Export $export
    ): string {
        return 'Screening Export Ready';
    }

    public static function getCompletedNotificationBody(
        Export $export
    ): string {
        $body = 'Your screening export has completed and '
            . Str::of('row')->counted($export->successful_rows)
            . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '
                . Str::of('row')->counted($failedRowsCount)
                . ' failed to export.';
        }

        return $body;
    }

    public function getFormats(): array
    {
        return [
            ExportFormat::Xlsx,
        ];
    }
}
