<?php

namespace App\Filament\Resources\Screenings\Pages;

use App\Filament\Resources\Screenings\ScreeningResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateScreening extends CreateRecord
{
    protected static string $resource = ScreeningResource::class;

    protected Width | string | null $maxContentWidth = Width::Full;
}
