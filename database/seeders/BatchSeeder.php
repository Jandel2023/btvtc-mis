<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Ntp;
use Illuminate\Database\Seeder;

class BatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $batches = [
            ['batch_code' => 'BT-RQM10-2023-STEP-0837-0005', 'batch_name' => 'EIM NC II Batch 0005', 'rqm_code' => 'RQM10-2023-STEP-0837-0005'],
            ['batch_code' => 'BT-RQM10-2023-STEP-0837-0006', 'batch_name' => 'Service Electrical System Batch 0006', 'rqm_code' => 'RQM10-2023-STEP-0837-0006'],
            ['batch_code' => 'BT-RQM10-2023-STEP-0837-0007', 'batch_name' => 'Motorcycle/Small Engine Servicing Batch 0007', 'rqm_code' => 'RQM10-2023-STEP-0837-0007'],
            ['batch_code' => 'BT-RQM31-2025-TTSP-0837-0003', 'batch_name' => 'EIM NC II Batch 0003', 'rqm_code' => 'RQM31-2025-TTSP-0837-0003'],
            ['batch_code' => 'BT-RQM38-2026-STEP-0837-0009', 'batch_name' => 'EIM NC II Batch 0009', 'rqm_code' => 'RQM38-2026-STEP-0837-0009'],
            ['batch_code' => 'BT-RQM38-2026-STEP-0837-0010', 'batch_name' => 'EIM NC II Batch 0010', 'rqm_code' => 'RQM38-2026-STEP-0837-0010'],
            ['batch_code' => 'BT-RQM31-2025-TTSP-0837-0010', 'batch_name' => 'Motorcycle/Small Engine Servicing Batch 0010', 'rqm_code' => 'RQM31-2025-TTSP-0837-0010'],
        ];

        foreach ($batches as $batch) {
            $ntp = Ntp::query()->where('rqm_code', $batch['rqm_code'])->firstOrFail();

            Batch::updateOrCreate(
                ['batch_code' => $batch['batch_code']],
                [
                    'ntp_id' => $ntp->id,
                    'qualification_id' => $ntp->qualification_id,
                    'scholarship_program' => $ntp->scholarship_program,
                    'batch_name' => $batch['batch_name'],
                    'start_date' => $ntp->indicative_start_date,
                    'schedule' => 'To be announced',
                    'venue' => 'Leyte',
                    'status' => 'Upcoming',
                    'remarks' => 'Created from '.$ntp->rqm_code,
                ],
            );
        }
    }
}
