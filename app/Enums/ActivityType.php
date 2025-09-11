<?php

namespace App\Enums;

enum ActivityType: string
{
    case CASH = 'CASH';
    case OPERATING = 'OPERATING';
    case INVESTING = 'INVESTING';
    case FINANCING = 'FINANCING';

    // Helper function to get all keys
    public static function options(): array
    {
        return array_column(self::cases(), 'values');
    }
}
