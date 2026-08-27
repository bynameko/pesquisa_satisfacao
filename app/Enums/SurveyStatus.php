<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SurveyStatus: string implements HasLabel
{
    case Draft = 'draft';
    case Active = 'active';
    case Closed = 'closed';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Draft => 'Rascunho',
            self::Active => 'Ativa',
            self::Closed => 'Encerrada',
        };
    }
}
