<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ScholarshipProgram: string implements HasLabel
{
    case LGU_Livelihood = 'lgu_livelihood';
    case TTSP = 'ttsp';
    case STEP = 'step';
    case TWSP = 'twsp';
    case OTHER = 'other';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::LGU_Livelihood => 'LGU Livelihood',
            self::TTSP => 'TTSP',
            self::STEP => 'STEP',
            self::TWSP => 'TWSP',
            self::OTHER => 'Other',
        };
    }

}

         
