<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum InviteStatus: string implements HasLabel
{
    case Pending = 'pending';
    case Answered = 'answered';
    case Expired = 'expired';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => 'Pendente',
            self::Answered => 'Respondido',
            self::Expired => 'Expirado',
        };
    }
}
