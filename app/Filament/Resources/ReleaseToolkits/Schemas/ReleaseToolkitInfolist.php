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
                TextEntry::make('batch.batch_name')
                    ->label('Batch'),
                TextEntry::make('screening.full_name')
                    ->label('Screening'),
                TextEntry::make('date_recieved')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('Notes')
                    ->label('Notes')
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
