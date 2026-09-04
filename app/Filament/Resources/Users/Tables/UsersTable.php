<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->modifyQueryUsing(function ($query) {
                $currentUserId = Auth::id();

                if ($currentUserId !== null) {
                    $query->whereKeyNot($currentUserId);
                }

                return $query;
            })
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('qualification.qualification_code')
                    ->copyable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('dob')
                    ->label('Birthdate')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('role')
                    ->badge()
                    ->searchable(),
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
