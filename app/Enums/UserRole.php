<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasLabel
{
    case Administrator = 'administrator';
    case Manager = 'manager';
    case Registrar = 'registrar';
    case Trainer = 'trainer';
    case Toolkeeper = 'toolkeeper';
    case Processor = 'processor';
    case Staff = 'staff';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Administrator => 'Administrator',
            self::Manager => 'Manager',
            self::Registrar => 'Registrar',
            self::Trainer => 'Trainer',
            self::Toolkeeper => 'Toolkeeper',
            self::Processor => 'Processor',
            self::Staff => 'Staff',

        };
    }
}
