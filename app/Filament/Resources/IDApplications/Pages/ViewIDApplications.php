<?php

namespace App\Filament\Resources\IDApplications\Pages;

use App\Filament\Resources\IDApplications\IDApplicationsResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewIDApplications extends ViewRecord
{
    protected static string $resource = IDApplicationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
