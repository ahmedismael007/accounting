<?php

namespace App\Enums;

enum IndustryType: string
{
    case ACCOUNTING_FIRM = 'ACCOUNTING_FIRM';
    case ADMINISTRATIVE_AND_SUPPORT_SERVICES = 'ADMINISTRATIVE_AND_SUPPORT_SERVICES';
    case AGRICULTURE = 'AGRICULTURE';
    case ART_AND_ENTERTAINMENT = 'ART_AND_ENTERTAINMENT';
    case CONTRACTING = 'CONTRACTING';
    case CONSULTING = 'CONSULTING';
    case EDUCATION = 'EDUCATION';
    case FINANCE = 'FINANCE';
    case HEALTH_CARE = 'HEALTH_CARE';
    case HOTELS = 'HOTELS';
    case LAW_FIRM = 'LAW_FIRM';
    case MANUFACTURING = 'MANUFACTURING';
    case MARKETING = 'MARKETING';
    case OTHER = 'OTHER';
    case TECHNOLOGY = 'TECHNOLOGY';
    case TRANSPORT = 'TRANSPORT';

    public static function options(): array
    {
        return array_column(self::cases(), 'value');
    }
}
