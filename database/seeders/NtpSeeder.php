<?php

namespace Database\Seeders;

use App\Models\Ntp;
use App\Models\Qualifications;
use Illuminate\Database\Seeder;

class NtpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $qualificationIds = [
            'EIM' => $this->qualificationId('EIM'),
            'MSES' => $this->qualificationId('MSES'),
        ];

        Ntp::insert([
            ['rqm_code' => 'RQM10-2023-STEP-0837-0005', 'qualification_id' => $qualificationIds['EIM'], 'scholarship_program' => 'step', 'approve_slots' => 16, 'total_amount' => 183920, 'indicative_start_date' => '2023-06-24', 'date_received' => '2023-06-24', 'note' => 'Province: Leyte'],
            ['rqm_code' => 'RQM10-2023-STEP-0837-0006', 'qualification_id' => $qualificationIds['EIM'], 'scholarship_program' => 'step', 'approve_slots' => 15, 'total_amount' => 60300, 'indicative_start_date' => '2023-07-10', 'date_received' => '2023-07-10', 'note' => 'Province: Leyte'],
            ['rqm_code' => 'RQM10-2023-STEP-0837-0007', 'qualification_id' => $qualificationIds['MSES'], 'scholarship_program' => 'step', 'approve_slots' => 15, 'total_amount' => 102660, 'indicative_start_date' => '2023-08-07', 'date_received' => '2023-08-07', 'note' => 'Province: Leyte'],
            ['rqm_code' => 'RQM31-2025-TTSP-0837-0003', 'qualification_id' => $qualificationIds['EIM'], 'scholarship_program' => 'ttsp', 'approve_slots' => 25, 'total_amount' => 263875, 'indicative_start_date' => '2026-07-28', 'date_received' => '2026-07-28', 'note' => 'Province: Leyte'],
            ['rqm_code' => 'RQM38-2026-STEP-0837-0009', 'qualification_id' => $qualificationIds['EIM'], 'scholarship_program' => 'step', 'approve_slots' => 20, 'total_amount' => 236700, 'indicative_start_date' => '2026-08-03', 'date_received' => '2026-08-03', 'note' => 'Province: Leyte'],
            ['rqm_code' => 'RQM38-2026-STEP-0837-0010', 'qualification_id' => $qualificationIds['EIM'], 'scholarship_program' => 'step', 'approve_slots' => 21, 'total_amount' => 248535, 'indicative_start_date' => '2026-08-10', 'date_received' => '2026-08-10', 'note' => 'Province: Leyte'],
            ['rqm_code' => 'RQM31-2025-TTSP-0837-0010', 'qualification_id' => $qualificationIds['MSES'], 'scholarship_program' => 'ttsp', 'approve_slots' => 25, 'total_amount' => 915150, 'indicative_start_date' => '2026-08-18', 'date_received' => '2026-08-18', 'note' => 'Province: Leyte'],
        ]);

    }

    protected function qualificationId(string $code): int
    {
        $qualificationId = Qualifications::query()
            ->where('qualification_code', $code)
            ->value('id');

        if ($qualificationId === null) {
            throw new \RuntimeException("Qualification not found: {$code}");
        }

        return (int) $qualificationId;
    }
}
