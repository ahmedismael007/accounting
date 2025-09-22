<?php

namespace App\Enums;

enum EmployeesCountType: string
{
    case ONE_TO_TEN = 'ONE_TO_TEN';
    case ELEVEN_TO_FIFTY = 'ELEVEN_TO_FIFTY';
    case FIFTY_ONE_TO_HUNDRED = 'FIFTY_ONE_TO_HUNDRED';
    case MORE_THAN_HUNDRED = 'MORE_THAN_HUNDRED';

    public static function options(): array
    {
        return array_column(self::cases(), 'value');
    }
}
