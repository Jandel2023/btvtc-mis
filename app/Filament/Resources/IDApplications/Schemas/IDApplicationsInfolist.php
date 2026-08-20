<?php

namespace App\Filament\Resources\IDApplications\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class IDApplicationsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('application_number'),
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('qualification_id')
                    ->numeric(),
                TextEntry::make('scholarship_program')
                    ->placeholder('-'),
                TextEntry::make('user_role')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('application_date')
                    ->date(),
                TextEntry::make('reason')
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('approved_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('approved_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('remarks')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
