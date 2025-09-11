<?php

namespace App\Services\v1;

use App\Enums\CountryType;
use App\Enums\CurrencyType;
use App\Models\Tenant\Accounting\Accountants\Account;
use Illuminate\Support\Collection;

class ReferenceValueFormatterService
{
    public function format(array|Collection $data): array
    {
        $data = $data instanceof Collection ? $data->toArray() : $data;

        if (array_is_list($data) && isset($data[0]) && is_array($data[0])) {
            return array_map(fn($row) => $this->format($row), $data);
        }

        return collect($data)->mapWithKeys(function ($value, $key) {
            return [$key => $this->formatValue($key, $value)];
        })->toArray();
    }

    protected function formatValue(string $key, mixed $value): mixed
    {
        return match ($key) {
            'currency' => $this->getCurrencyLabel($value),
            'country'  => $this->getCountryLabel($value),
            'account_id'  => $this->getAccountName($value),
            default    => $value,
        };
    }

    protected function getCurrencyLabel(?string $value): ?string
    {
        return collect(CurrencyType::options())
            ->firstWhere('value', $value)['label'] ?? $value;
    }

    protected function getCountryLabel(?string $value): ?string
    {
        return collect(CountryType::options())
            ->firstWhere('value', $value)['label'] ?? $value;
    }

    protected function getAccountName(?int $accountId): ?string
    {
        if (! $accountId) {
            return null;
        }

        return Account::find($accountId)?->name_ar ?? $accountId;
    }
}
