<?php

namespace App\Filament\Resources\Trainees\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TraineeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('screening_id')
                    ->numeric(),
                TextEntry::make('name'),
                TextEntry::make('phone')
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('qr_code')
                    ->placeholder('-'),
                TextEntry::make('dob')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('gender')
                    ->placeholder('-'),
                TextEntry::make('picture')
                    ->placeholder('-'),
                TextEntry::make('remarks')
                    ->placeholder('-'),
                TextEntry::make('requirements')
                    ->placeholder('-'),
                TextEntry::make('date_enrolled')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
