<?php

namespace App\Filament\Resources\Screenings\Tables;

use App\Filament\Exports\ScreeningExporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;


class ScreeningsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->searchable(),
                // TextColumn::make('aptitude_score')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('interview_score')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('total_score')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                // TextColumn::make('phone')
                //     ->searchable(),
                TextColumn::make('batch.batch_name')
                    ->sortable(),
                TextColumn::make('enrolled_status')
                 ->label('Enrollment Status')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (?Bool $state): string => match ($state) {
                        true => 'Enrolled',
                        false => 'Not Enrolled',
                      
                    })
                    ->badge()
                    ->color(fn (?Bool $state): string => match ($state) {
                        true => 'success',
                        false => 'danger',
                        default => 'success',
                    }),
                  
                // TextColumn::make('address')
                //     ->searchable(),
                // TextColumn::make('date_screened')
                //     ->date()
                //     ->sortable(),
                // TextColumn::make('remarks')
                //     ->searchable(),
                // TextColumn::make('screened_by')
                //     ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])

            ->headerActions([
                ExportAction::make('export')
                    ->label('Export Screenings')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->exporter(ScreeningExporter::class)
                    ->formats([
                        ExportFormat::Csv,
                ])
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
