<?php

namespace App\Filament\Resources\ReleaseToolkits\Pages;

use App\Filament\Resources\ReleaseToolkits\ReleaseToolkitResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditReleaseToolkit extends EditRecord
{
    protected static string $resource = ReleaseToolkitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
