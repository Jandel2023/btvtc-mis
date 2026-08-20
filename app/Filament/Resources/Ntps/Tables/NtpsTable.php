<?php

namespace App\Filament\Resources\Ntps\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NtpsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rqm_code')
                    ->searchable(),
                TextColumn::make('qualification.qualification_code')
                    ->label('Qualification')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('scholarship_program')
                    ->searchable(),
                TextColumn::make('approve_slots')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('indicative_start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('date_approve_by_tesda')
                    ->date()
                    ->sortable(),
                TextColumn::make('date_received')
                    ->date()
                    ->sortable(),
                TextColumn::make('note')
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
