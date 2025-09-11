<?php

namespace App\Enums;

enum BankAccountType: string
{
    case BANK_ACCOUNT = 'BANK_ACCOUNT';
    case CREDIT_CARD = 'CREDIT_CARD';
    case PETTY_CASH = 'PETTY_CASH';

    public static function options(): array
    {
        return array_column(self::cases(), 'value');
    }
}
