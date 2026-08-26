<?php

namespace App\Filament\Resources\ReleaseToolkits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                    ->searchable()
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
