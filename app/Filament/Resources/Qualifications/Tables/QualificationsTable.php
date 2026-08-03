<?php

namespace App\Filament\Resources\Qualifications\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\SelectColumn;
use Filament\Forms\Components\Select;

class QualificationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('qualification_code')
                    ->searchable(),
                TextColumn::make('qualification_title')
                    ->searchable(),
               TextColumn::make('qualificationLevel.code')
                    ->searchable(),
                TextColumn::make('trainingSector.sector_name')
                    ->searchable(),
                TextColumn::make('training_hours')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('competency_standard')
                    ->searchable()
                    ->wrap()
                     ->lineClamp(1),
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
