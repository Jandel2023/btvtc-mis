<?php

namespace App\Filament\Resources\ReleaseToolkits\Pages;

use App\Filament\Resources\ReleaseToolkits\ReleaseToolkitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReleaseToolkits extends ListRecords
{
    protected static string $resource = ReleaseToolkitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
