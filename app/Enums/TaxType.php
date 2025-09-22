<?php

namespace App\Enums;

enum TaxType: string
{
    case OUT_OF_SCOPE = 'OUT_OF_SCOPE';
    case REVERSE_CHARGE = 'REVERSE_CHARGE';
    case PURCHASES = 'PURCHASES';
    case SALES = 'SALES';

    public static function options(): array
    {
        return array_column(self::cases(), 'value');
    }
}
