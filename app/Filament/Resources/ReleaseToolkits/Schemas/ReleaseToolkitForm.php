<?php

namespace App\Filament\Resources\ReleaseToolkits\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReleaseToolkitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('batch_id')
                    ->relationship('batch', 'batch_name')
                    ->required(),
                Select::make('screening_id')
                    ->relationship('screening', 'fname')
                    ->getOptionLabelFromRecordUsing(fn ($record) => trim(implode(' ', array_filter([
                        $record->fname,
                        $record->mname,
                        $record->lname,
                    ]))))
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('date_recieved')
                    ->required(),
                TextInput::make('Notes')
                    ->label('Notes')
                    ->nullable(),
            ]);
    }
}
