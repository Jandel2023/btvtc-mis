<?php

namespace App\Filament\Resources\Screenings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ScreeningInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('fname'),
                TextEntry::make('lname'),
                TextEntry::make('mname')
                    ->placeholder('-'),
                TextEntry::make('aptitude_score')
                    ->numeric(),
                TextEntry::make('interview_score')
                    ->numeric(),
                TextEntry::make('total_score')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('phone')
                    ->placeholder('-'),
                TextEntry::make('batch.batch_name')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->placeholder('-'),
                TextEntry::make('address')
                    ->placeholder('-'),
                TextEntry::make('date_screened')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('remarks')
                    ->placeholder('-'),
                TextEntry::make('screened_by')
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
