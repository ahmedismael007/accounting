<?php

namespace App\Http\Requests\Tenant\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class CreateItemRequest extends FormRequest
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
            'sku' => ['sometimes', 'string', 'max:100'],
            'name' => ['required', 'array'],
            'name.*' => ['sometimes', 'string'],
            'description' => ['sometimes', 'array'],
            'description.*' => ['sometimes', 'string'],
            'unit_cost' => ['sometimes', 'numeric'],
            'unit_price' => ['sometimes', 'numeric'],
            'expense_account' => ['sometimes', 'exists:accounts,id'],
            'revenue_account' => ['sometimes', 'exists:accounts,id'],
            'purchase_tax_rate' => ['sometimes', 'numeric', 'exists:tax_rates,id'],
            'revenue_tax_rate' => ['sometimes', 'numeric', 'exists:tax_rates,id'],
            'quantity' => ['sometimes', 'numeric'],
            'avg_unit_cost' => ['sometimes', 'numeric'],
            'inventory_value' => ['sometimes', 'numeric'],
            'measurement_unit' => ['sometimes', 'string', 'max:50'],
            'warehouse_id' => ['numeric', 'required'],
        ];
    }
}
