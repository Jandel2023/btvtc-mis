<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

enum ScheduleType: string implements HasLabel
{
    //
   
    case MONDAY = 'Monday';
    case TUESDAY = 'Tuesday';
    case WEDNESDAY = 'Wednesday';
    case THURSDAY = 'Thursday';
    case FRIDAY = 'Friday';
    case SATURDAY = 'Saturday';
     case SUNDAY = 'Sunday';
   
   

    public function getLabel(): ?string
    {
        return match ($this) {
           
            self::MONDAY => 'Monday',
            self::TUESDAY => 'Tuesday',
            self::WEDNESDAY => 'Wednesday',
            self::THURSDAY => 'Thursday',
            self::FRIDAY => 'Friday',
            self::SATURDAY => 'Saturday',
             self::SUNDAY => 'Sunday',
          
           
        };
    }
}
