<?php

namespace App\Filament\Resources\Ntps\Schemas;

use App\Enums\ScholarshipProgram;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class NtpForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('rqm_code')
                    ->label('RQM CODE')
                    ->required(),
                Select::make('qualification_id')
                    ->relationship(
                        'qualification','qualification_code',
                        fn ($query) => $query->where('is_active', true),
                    )
                    ->required(),
                    
                Select::make('scholarship_program')
                    ->label('Scholarship Program')
                    ->options(ScholarshipProgram::class)
                    ->required(),
                TextInput::make('approve_slots')
                    ->numeric()
                    ->required(),
               TextInput::make('total_amount')
                    ->label('Total Amount')
                    ->numeric()
                    ->prefix('₱')
                    ->step(0.01)
                    ->required(),
                DatePicker::make('indicative_start_date'),
                DatePicker::make('date_approve_by_tesda'),
                DatePicker::make('date_received'),
                TextInput::make('note'),
            ]);
    }
}
