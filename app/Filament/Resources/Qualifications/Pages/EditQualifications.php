<?php

namespace App\Filament\Resources\Qualifications\Pages;

use App\Filament\Resources\Qualifications\QualificationsResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use App\Models\Qualifications;

class EditQualifications extends EditRecord
{
    protected static string $resource = QualificationsResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
{
    $data['qualification_code'] = Qualifications::generateQualificationCode(
        $data['qualification_title'],
        $data['qualification_level_id']
    );

    return $data;
}

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

      protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
