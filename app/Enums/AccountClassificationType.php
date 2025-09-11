<?php

namespace App\Enums;

enum AccountClassificationType: string
{
    case REVENUE = 'REVENUE';
    case EXPENSE = 'EXPENSE';
    case ASSET = 'ASSET';
    case LIABILITY = 'LIABILITY';
    case EQUITY = 'EQUITY';

    public static function options(): array
    {
        return array_column(self::cases(), 'value');
    }
}
