<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ScholarshipProgram: string implements HasLabel
{
    case LGU_Livelihood = 'lgu_livelihood';
    case Others = 'others';
    case TTSP = 'ttsp';
    case STEP = 'step';
    case TWSP = 'twsp';
   

    public function getLabel(): ?string
    {
        return match ($this) {
            self::LGU_Livelihood => 'LGU Livelihood',
            self::Others => 'Others',
            self::TTSP => 'TTSP',
            self::STEP => 'STEP',
            self::TWSP => 'TWSP',
           
        };
    }

}

         
