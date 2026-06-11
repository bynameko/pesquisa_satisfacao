<?php

namespace App\Enums;

enum InviteStatus: string
{
    case Pending = 'pending';
    case Answered = 'answered';
    case Expired = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendente',
            self::Answered => 'Respondido',
            self::Expired => 'Expirado',
        };
    }
}
