<?php

namespace App\Filament\Resources\Qualifications\Pages;

use App\Filament\Resources\Qualifications\QualificationsResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Qualifications;


class CreateQualifications extends CreateRecord
{
    protected static string $resource = QualificationsResource::class;

            protected function mutateFormDataBeforeCreate(array $data): array
        {
            $data['qualification_code'] = Qualifications::generateQualificationCode(
                $data['qualification_title'],
                $data['qualification_level_id']
            );

            return $data;
        }
}
