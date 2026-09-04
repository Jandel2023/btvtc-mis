<?php

namespace App\Filament\Resources\ReleaseToolkits\Tables;

use App\Filament\Exports\ReleaseToolkitExporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReleaseToolkitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('batch.batch_name')
                    ->label('Batch')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('screening.full_name')
                    ->label('Applicant')
                    ->searchable(query: function (Builder $query, string $search): void {
                        $query->whereHas('screening', function (Builder $query) use ($search): void {
                            $query->where(function (Builder $query) use ($search): void {
                                $query
                                    ->where('fname', 'like', "%{$search}%")
                                    ->orWhere('mname', 'like', "%{$search}%")
                                    ->orWhere('lname', 'like', "%{$search}%");
                            });
                        });
                    })
                    ->sortable(),
                TextColumn::make('date_recieved')
                    ->label('Date Received')
                    ->date()
                    ->sortable(),
                TextColumn::make('Notes')
                    ->label('Notes')
                    ->wrap()
                    ->limit(30),
            ])
            ->filters([
                SelectFilter::make('batch_id')
                    ->label('Batch')
                    ->relationship('batch', 'batch_name'),
                Filter::make('date_recieved')
                    ->label('Date Received')
                    ->form([
                        DatePicker::make('from')
                            ->label('From'),
                        DatePicker::make('until')
                            ->label('Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn (Builder $query, string $date): Builder => $query->whereDate('date_recieved', '>=', $date),
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn (Builder $query, string $date): Builder => $query->whereDate('date_recieved', '<=', $date),
                            );
                    }),
            ])
            ->headerActions([
                ExportAction::make('export')
                    ->label('Export Release Toolkits')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->exporter(ReleaseToolkitExporter::class)
                    ->formats([
                        ExportFormat::Csv,
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
