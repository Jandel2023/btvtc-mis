<?php

namespace App\Filament\Resources\Qualifications\Schemas;

use App\Models\QualificationLevel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class QualificationsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('qualification_code')
                    ->required(),
                TextInput::make('qualification_title')
                    ->required(),
                Select::make('qualification_level_id')
                    ->label('QualificationLevel')
                    ->relationship('qualificationLevel','code')
                    ->required(),
                Select::make('training_sector_id')
                    ->relationship('trainingSector','sector_name')
                    ->required(),
                TextInput::make('training_hours')
                    ->required()
                    ->numeric(),
                TextInput::make('competency_standard'),
                Textarea::make('description')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
