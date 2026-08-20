<?php

namespace App\Filament\Resources\IDApplications\Pages;

use App\Filament\Resources\IDApplications\IDApplicationsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIDApplications extends ListRecords
{
    protected static string $resource = IDApplicationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
