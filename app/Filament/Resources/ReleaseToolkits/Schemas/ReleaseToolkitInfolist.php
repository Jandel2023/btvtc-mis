<?php

namespace App\Filament\Resources\ReleaseToolkits\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ReleaseToolkitInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('first_name'),
                TextEntry::make('middle_name')
                    ->placeholder('-'),
                TextEntry::make('last_name'),
                TextEntry::make('qualification_id')
                    ->numeric(),
                TextEntry::make('contact_number')
                    ->placeholder('-'),
                TextEntry::make('scholarship_program'),
                TextEntry::make('date_recieved')
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
