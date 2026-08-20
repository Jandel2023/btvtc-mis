<?php

namespace App\Filament\Resources\Ntps\Pages;

use App\Filament\Resources\Ntps\NtpResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNtps extends ListRecords
{
    protected static string $resource = NtpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
