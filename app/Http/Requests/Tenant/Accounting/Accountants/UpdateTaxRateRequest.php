<?php

namespace App\Http\Requests\Tenant\Accounting\Accountants;

use App\Enums\TaxType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaxRateRequest extends FormRequest
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
            'name' => 'sometimes|array',
            'name.*' => 'string|sometimes',
            'tax_type' => ['sometimes', 'string', Rule::in(array_column(TaxType::cases(), 'name'))],
            'tax_rate' => 'numeric|sometimes',
            'description' => 'sometimes|string',
        ];
    }
}
