<?php

namespace App\Filament\Resources\Trainees\Schemas;

use App\Models\Screening;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;

class TraineeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
             
          
          Select::make('screening_id')
            ->label('Name')
            ->options(function () {
                return Screening::query()
                    ->where('status', 'Passed')
                    ->where('enrolled_status', false)
                    ->with('batch.ntp')
                    ->get()
                    ->filter(function (Screening $screening) {
                        $ntp = $screening->batch?->ntp;

                        if (! $ntp || ! is_numeric($ntp->approve_slots)) {
                            return true;
                        }

                        $approvedCount = Screening::query()
                            ->where('enrolled_status', true)
                            ->whereHas('batch', function ($query) use ($ntp) {
                                $query->where('ntp_id', $ntp->id);
                            })
                            ->count();

                        return $approvedCount < (int) $ntp->approve_slots;
                    })
                    ->mapWithKeys(fn (Screening $screening) => [
                        $screening->id => $screening->full_name,
                    ])
                    ->all();
            })
            ->required()
            ->preload()
            ->live()
            ->afterStateUpdated(function ($state, Set $set) {
                if (! $state) {
                    $set('name', null);
                    $set('batch', null);

                    return;
                }

                $screening = Screening::with('batch')->find($state);

                if (! $screening) {
                    return;
                }

                $set('name', $screening->full_name);
                $set('batch', $screening->batch?->batch_name);
            })
             ->visible(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord),

                
                TextInput::make('batch')
                    ->label('Batch')
                    ->disabled()
                     ->readOnly()
                      ->visible(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord),
                  
                TextInput::make('name')
                    ->label('Name')
                    ->disabled()
                    ->dehydrated(false)
                    ->visible(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord)
                    ->afterStateHydrated(function ($state, $component, $record) {
                        if ($record && $record->screening) {
                            $component->state($record->screening->full_name);
                        }
                    }),


                   
                Toggle::make('enroll_status')
                    ->label('Enrollment Status')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('gray')
                    ->inline()
                    ->visible(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord),
                  
                   
                   
                

                 DatePicker::make('date_enrolled')
                        ->default(now())
                         ->visible(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord),


                    TextInput::make('phone')
                          ->visible(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord),
                    TextInput::make('email')
                        ->label('Email address')
                        ->email()
                          ->visible(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord),
        
                    TextInput::make('status')
                        ->default('Active')
                      ->visible(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord),
                    TextInput::make('remarks')
                        ->columnSpanFull(),
                   
            ]);
    }
}
