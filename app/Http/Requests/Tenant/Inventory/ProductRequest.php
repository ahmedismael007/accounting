<?php

namespace App\Http\Requests\Tenant\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'sku' => ['nullable', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'unit_cost' => ['nullable', 'numeric'],
            'unit_price' => ['nullable', 'numeric'],
            'expense_account' => ['nullable', 'exists:accounts,id'],
            'revenue_account' => ['nullable', 'exists:accounts,id'],
            'purchase_tax_rate' => ['nullable', 'numeric', 'exists:tax_rates,id'],
            'revenue_tax_rate' => ['nullable', 'numeric', 'exists:tax_rates,id'],
            'quantity' => ['nullable', 'numeric'],
            'avg_unit_cost' => ['nullable', 'numeric'],
            'inventory_value' => ['nullable', 'numeric'],
            'measurement_unit' => ['nullable', 'string', 'max:50'],
            'warehouse_id' => ['numeric', 'required'],
        ];
    }
}
