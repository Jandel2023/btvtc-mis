<?php

namespace App\Filament\Resources\Screenings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ScreeningsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fname')
                    ->searchable(),
                TextColumn::make('lname')
                    ->searchable(),
                TextColumn::make('mname')
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
