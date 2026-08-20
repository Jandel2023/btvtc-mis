<?php

namespace App\Filament\Resources\IDApplications\Pages;

use App\Filament\Resources\IDApplications\IDApplicationsResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditIDApplications extends EditRecord
{
    protected static string $resource = IDApplicationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
