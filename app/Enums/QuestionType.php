<?php

namespace App\Enums;

enum QuestionType: string
{
    case Rating5 = 'rating_5';
    case Rating10 = 'rating_10';
    case Text = 'text';

    public function label(): string
    {
        return match ($this) {
            self::Rating5 => 'Nota 1-5',
            self::Rating10 => 'Nota 1-10',
            self::Text => 'Texto',
        };
    }
}
