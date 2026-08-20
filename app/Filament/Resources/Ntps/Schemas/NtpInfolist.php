<?php

namespace App\Filament\Resources\Ntps\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class NtpInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('rqm_code')
                    ->placeholder('-'),
                TextEntry::make('qualification_id')
                    ->numeric(),
                TextEntry::make('scholarship_program')
                    ->placeholder('-'),
                TextEntry::make('approve_slots')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('total_amount')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('indicative_start_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('date_approve_by_tesda')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('date_received')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('note')
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
