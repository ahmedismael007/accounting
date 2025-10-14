<?php

namespace App\Http\Requests\Tenant\Accounting\Sales;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isCreate = $this->isMethod('post');

        return [
            // === Invoice core fields ===
            'customer_id' => [$isCreate ? 'required' : 'sometimes', 'exists:customers,id'],
            'invoice_number' => ['sometimes', 'string', 'max:255'],
            'currency' => [$isCreate ? 'required' : 'sometimes', 'string', 'max:10'],
            'date' => [$isCreate ? 'required' : 'sometimes', 'date'],
            'due_date' => [$isCreate ? 'required' : 'sometimes', 'date', 'after_or_equal:date'],
            'purchase_order' => ['nullable', 'string', 'max:255'],
            'reference' => ['nullable', 'string', 'max:255'],
            'project_id' => ['sometimes', 'exists:projects,id'],
            'warehouse_id' => ['sometimes', 'exists:warehouses,id'],
            'tax_amount_type' => [$isCreate ? 'required' : 'sometimes', 'in:tax_included,tax_excluded'],
            'notes' => ['nullable', 'string'],

            // === Line Items ===
            'line_items' => [$isCreate ? 'required' : 'sometimes', 'array', 'min:1'],
            'line_items.*.description' => [$isCreate ? 'required' : 'sometimes', 'string'],
            'line_items.*.quantity' => [$isCreate ? 'required' : 'sometimes', 'numeric', 'min:0.01'],
            'line_items.*.price' => [$isCreate ? 'required' : 'sometimes', 'numeric', 'min:0'],
            'line_items.*.item_id' => ['nullable', 'exists:items,id'],
            'line_items.*.tax_rate_id' => ['nullable', 'exists:tax_rates,id'],
            'line_items.*.cost_center_id' => ['nullable', 'exists:cost_centers,id'],
            'line_items.*.discount_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],

            // === Discount ===
            'discount' => ['nullable', 'array'],
            'discount.amount' => ['required_with:discount', 'numeric', 'min:0'],
            'discount.account_id' => ['required_with:discount', 'exists:accounts,id'],
            'discount.tax_rate_id' => ['nullable', 'exists:tax_rates,id'],
            'discount.cost_center_id' => ['nullable', 'exists:cost_centers,id'],

            // === Retention ===
            'retention' => ['nullable', 'array'],
            'retention.amount' => ['required_with:retention', 'numeric', 'min:0'],
            'retention.account_id' => ['required_with:retention', 'exists:accounts,id'],

            // === Totals ===
            'subtotal' => [$isCreate ? 'required' : 'sometimes', 'numeric', 'min:0'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'vat' => ['nullable', 'numeric', 'min:0'],
            'total' => [$isCreate ? 'required' : 'sometimes', 'numeric', 'min:0'],
            'net_due' => [$isCreate ? 'required' : 'sometimes', 'numeric', 'min:0'],
        ];
    }
}
