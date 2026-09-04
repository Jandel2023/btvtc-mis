<?php

namespace App\Filament\Resources\Batches\Tables;

use App\Models\Batch;
use App\Models\Screening;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BatchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('batch_code')
                    ->searchable(),
                TextColumn::make('available_slots')
                    ->label('Available Slots')
                    ->state(function (Batch $record): int {
                        if (! $record->ntp) {
                            return 0;
                        }

                        $enrolledCount = Screening::query()
                            ->where('enrolled_status', true)
                            ->whereHas('batch', function ($query) use ($record): void {
                                $query->where('ntp_id', $record->ntp_id);
                            })
                            ->count();

                        return max((int) $record->ntp->approve_slots - $enrolledCount, 0);
                    })
                    ->formatStateUsing(fn (int $state): string => $state === 0 ? 'Full' : (string) $state)
                    ->sortable(false),
                TextColumn::make('ntp.rqm_code')
                    ->searchable(),
                TextColumn::make('batch_name')
                    ->searchable(),
                TextColumn::make('qualification.qualification_code')
                    ->label('Qualification')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('scholarship_program')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('schedule')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('venue')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
