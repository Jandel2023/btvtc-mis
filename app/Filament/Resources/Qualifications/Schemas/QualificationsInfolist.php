<?php

namespace App\Filament\Resources\Qualifications\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class QualificationsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('qualification_code'),
                TextEntry::make('qualification_title'),
                TextEntry::make('qualification_level_id')
                    ->numeric(),
                TextEntry::make('training_sector_id')
                    ->numeric(),
                TextEntry::make('training_hours')
                    ->numeric(),
                TextEntry::make('competency_standard')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
