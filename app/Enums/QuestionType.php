<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum QuestionType: string implements HasLabel
{
    case Rating5 = 'rating_5';
    case Rating10 = 'rating_10';
    case Text = 'text';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Rating5 => 'Nota 1-5',
            self::Rating10 => 'Nota 1-10',
            self::Text => 'Texto',
        };
    }
}
