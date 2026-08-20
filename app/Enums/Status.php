<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Status: string implements HasLabel
{
    case Upcoming = 'upcoming';
    case Ongoing = 'ongoing';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
  


    public function getLabel(): ?string
    {
        return match ($this) {
          self::Upcoming => 'Upcoming',
          self::Ongoing => 'Ongoing',
          self::Completed => 'Completted',
          self::Cancelled => 'Cancelled',

        };
    }
}
