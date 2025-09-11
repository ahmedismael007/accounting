<?php

    namespace App\Http\Requests\Tenant\Accounting\LineItem;

    use Illuminate\Foundation\Http\FormRequest;

    class LineItemRequest extends FormRequest
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
                'description' => 'required|string|max:500',
                'account_id' => 'required|exists:accounts,id',
                'quantity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'discount' => 'nullable|integer|min:0|max:100',
                'product_id' => 'nullable|exists:products,id',
                'cost_center_id' => 'nullable|exists:cost_centers,id',
                'tax_rate_id' => 'nullable|exists:tax_rates,id',
                'line_itemable_id' => 'required|integer',
                'line_itemable_type' => 'required|string'
            ];
        }
    }
