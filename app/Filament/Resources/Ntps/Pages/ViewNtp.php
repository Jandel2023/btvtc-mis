<?php

namespace App\Filament\Resources\Ntps\Pages;

use App\Filament\Resources\Ntps\NtpResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewNtp extends ViewRecord
{
    protected static string $resource = NtpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
