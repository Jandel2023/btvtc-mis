<?php

namespace App\Filament\Resources\ReleaseToolkits\Schemas;

use App\Models\ReleaseToolkit;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class ReleaseToolkitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('batch_id')
                    ->relationship('batch', 'batch_name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(fn (Set $set): mixed => $set('screening_id', null))
                    ->required(),
                Select::make('screening_id')
                    ->relationship(
                        'screening',
                        'fname',
                        modifyQueryUsing: function (Builder $query, Get $get): void {
                            $query
                                ->where('enrolled_status', true)
                                ->when(
                                    $get('batch_id'),
                                    fn (Builder $query, int|string $batchId): Builder => $query
                                        ->where('batch_id', $batchId)
                                        ->whereNotIn(
                                            'id',
                                            ReleaseToolkit::query()
                                                ->select('screening_id')
                                                ->where('batch_id', $batchId),
                                        ),
                                    fn (Builder $query): Builder => $query->whereRaw('1 = 0'),
                                );
                        },
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => trim(implode(' ', array_filter([
                        $record->fname,
                        $record->mname,
                        $record->lname,
                    ]))))
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('date_recieved')
                    ->default(now())
                    ->required(),
                TextInput::make('Notes')
                    ->label('Notes')
                    ->nullable(),
            ]);
    }
}
