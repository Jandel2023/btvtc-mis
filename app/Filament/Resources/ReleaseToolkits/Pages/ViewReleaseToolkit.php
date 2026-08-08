<?php

namespace App\Filament\Resources\ReleaseToolkits\Pages;

use App\Filament\Resources\ReleaseToolkits\ReleaseToolkitResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewReleaseToolkit extends ViewRecord
{
    protected static string $resource = ReleaseToolkitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
