<?php

namespace App\Filament\Resources\Trainees\Pages;

use App\Filament\Resources\Trainees\TraineeResource;
use App\Models\Screening;
use Filament\Resources\Pages\CreateRecord;

class CreateTrainee extends CreateRecord
{
    protected static string $resource = TraineeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['enroll_status'] = true;

        if (! empty($data['screening_id'])) {
            $screening = Screening::with('batch')->find($data['screening_id']);

            if ($screening) {
                $data['name'] = $screening->full_name;
                $data['batch'] = $screening->batch?->batch_name;
            }
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $screening = Screening::find($this->record->screening_id);

        if ($screening) {
            $screening->update([
                'enrolled_status' => true,
            ]);
        }
    }
}
