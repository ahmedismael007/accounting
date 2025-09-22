<?php

namespace App\Http\Requests\Central\Tenant;

use App\Enums\CountryType;
use App\Enums\CurrencyType;
use App\Enums\EmployeesCountType;
use App\Enums\IndustryType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TenantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'domain' => 'nullable|string',
            'industry' => ['required', 'string', Rule::in(array_column(IndustryType::cases(), 'name'))],
            'employees_count' => ['nullable', 'string', Rule::in(array_column(
                EmployeesCountType::cases(),
                'name'
            ))],
            'currency' => ['nullable', 'string', Rule::in(array_column(CurrencyType::cases(), 'name'))],
            'is_vat_registered' => ['nullable', 'boolean'],
            'fiscal_year_end' => 'nullable|string|in:01,02,03,04,05,06,07,08,09,10,11,12',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',

            'commercial_number' => 'string|nullable|size:10',
            'tax_number' => [
                'nullable',
                'string',
                'size:15',
                'regex:/^3[0-9]{13}3$/'
            ],
            'tax_registeration_date' => 'nullable|date',
            'tax_first_due_date' => 'nullable|date',
            'tax_period' => 'nullable|string|in:monthly,quarter',

            'building_number' => 'nullable|string',
            'street' => 'nullable|string',
            'district' => 'nullable|string',
            'city' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'country' => ['nullable', 'string', Rule::in(array_column(CountryType::cases(), 'name'))],

        ];
    }
}
