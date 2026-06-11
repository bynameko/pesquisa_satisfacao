<?php

namespace App\Enums;

enum SurveyStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Rascunho',
            self::Active => 'Ativa',
            self::Closed => 'Encerrada',
        };
    }
}
