<?php

namespace App\Filament\Resources\Screenings\Tables;

use App\Filament\Exports\ScreeningExporter;
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


class ScreeningsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->searchable(),
                TextColumn::make('aptitude_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('interview_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('qualification.qualification_code')
                    ->label('Qualification')
                    ->searchable(),
                TextColumn::make('qualification.qualification_code')
                    ->label('Qualification Code')
                    ->searchable(),
                TextColumn::make('qualification.qualificationLevel.code')
                    ->label('Qualification Level')
                    ->searchable(),
                TextColumn::make('scholarship_program')
                    ->searchable(),
                TextColumn::make('address')
                    ->searchable(),
                TextColumn::make('date_screened')
                    ->date()
                    ->sortable(),
                TextColumn::make('remarks')
                    ->searchable(),
                TextColumn::make('screened_by')
                    ->searchable(),
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
                SelectFilter::make('status')
                    ->options([
                        'Passed' => 'Passed',
                        'Failed' => 'Failed',
                    ]),
                SelectFilter::make('qualification')
                    ->relationship('qualification', 'qualification_code')
                    ->searchable()
                    ->preload(),
                Filter::make('date_screened')
                    ->schema([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, string $date): Builder => $query->whereDate('date_screened', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, string $date): Builder => $query->whereDate('date_screened', '<=', $date),
                            );
                    }),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Export Screening Result')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->exporter(ScreeningExporter::class)
                    ->formats([
                        ExportFormat::Xlsx,
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
