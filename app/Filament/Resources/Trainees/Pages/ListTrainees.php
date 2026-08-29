<?php

namespace App\Filament\Resources\Trainees\Pages;

use App\Filament\Resources\Trainees\TraineeResource;
use App\Models\Trainee;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTrainees extends ListRecords
{
    protected static string $resource = TraineeResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Trainees')
                ->badge(fn (): int => Trainee::query()->count())
                ->modifyQueryUsing(fn (Builder $query) => $query),
            'enrolled' => Tab::make('Enrolled')
                ->badge(fn (): int => Trainee::query()->where('enroll_status', true)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('enroll_status', true)),
            'not_enrolled' => Tab::make('Not Enrolled')
                ->badge(fn (): int => Trainee::query()->where('enroll_status', false)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('enroll_status', false)),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'all';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
