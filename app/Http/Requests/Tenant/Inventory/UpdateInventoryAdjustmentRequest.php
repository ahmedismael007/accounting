<?php

namespace App\Http\Requests\Tenant\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryAdjustmentRequest extends FormRequest
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
            'status' => ['sometimes', 'in:DRAFT,APPROVED'],
            'reference' => ['sometimes', 'string', 'max:255'],
            'date' => ['sometimes', 'date'],
            'warehouse_id' => ['sometimes', 'exists:warehouses,id'],
            'item_id' => ['sometimes', 'exists:items,id'],
            'line_item_description' => ['sometimes', 'string'],
            'qty' => ['sometimes', 'numeric'],
            'inventory_value' => ['sometimes', 'numeric'],
            'account_id' => ['sometimes', 'nullable', 'exists:accounts,id'],
        ];
    }
}
