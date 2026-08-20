<?php

namespace App\Filament\Resources\Batches\Pages;

use App\Filament\Resources\Batches\BatchResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBatch extends CreateRecord
{
    protected static string $resource = BatchResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $qualificationId = $data['qualification_id'] ?? null;
        $scholarshipProgram = $data['scholarship_program'] ?? null;

        if ($qualificationId && $scholarshipProgram) {
            $qualification = \App\Models\Qualifications::find($qualificationId);

            if ($qualification) {
                $scholarshipValue = $scholarshipProgram instanceof \App\Enums\ScholarshipProgram
                    ? $scholarshipProgram->value
                    : $scholarshipProgram;

                $count = \App\Models\Batch::where(
                    'qualification_id',
                    $qualificationId
                )
                    ->where(
                        'scholarship_program',
                        $scholarshipValue
                    )
                    ->count() + 1;

                $data['batch_code'] =
                    $qualification->qualification_code
                    . '-'
                    . strtoupper($scholarshipValue)
                    . '-'
                    . str_pad($count, 3, '0', STR_PAD_LEFT);
            }
        }

        return $data;
    }
}
