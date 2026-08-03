<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasLabel
{
    case Administrator = 'administrator';
    case Registrar = 'registrar';
    case Trainer = 'trainer';
    case Student = 'student';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Administrator => 'Administrator',
            self::Registrar => 'Registrar',
            self::Trainer => 'Trainer',
            self::Student => 'Student',
        };
    }
}
