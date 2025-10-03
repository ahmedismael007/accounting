<?php

namespace App\Http\Requests\Tenant\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class CreateInventoryAdjustmentRequest extends FormRequest
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
            'status' => ['required', 'in:DRAFT,APPROVED'],
            'reference' => ['nullable', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'currency' => ['required', 'string', 'size:3'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'item_id' => ['required', 'exists:items,id'],
            'line_item_description' => ['nullable', 'string'],
            'qty' => ['required', 'numeric'],
            'inventory_value' => ['required', 'numeric'],
            'account_id' => ['nullable', 'exists:accounts,id'],
        ];
    }
}
