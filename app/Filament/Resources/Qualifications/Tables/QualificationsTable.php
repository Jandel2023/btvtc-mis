<?php

namespace App\Filament\Resources\Qualifications\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QualificationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('qualification_code')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('qualification_title')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('qualificationLevel.code')
                    ->label('Qualification Level')
                    ->copyable()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('trainingSector.sector_name')
                    ->label('Training Sector')
                    ->copyable()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('training_hours')
                    ->label('Training Hours')
                    ->formatStateUsing(function ($state) {
                        if (! $state) {
                            return '-';
                        }

                        $days = ceil($state / 8);

                        return "{$state} hrs / {$days} days";
                    })
                    ->sortable(),
                // TextColumn::make('competency_standard')
                //     ->searchable(),
                IconColumn::make('is_active')
                    ->boolean(),
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
