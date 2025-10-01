<?php

namespace App\Http\Requests\Tenant\Accounting\Accountants;

use Illuminate\Foundation\Http\FormRequest;

class CreateAccountRequest extends FormRequest
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
            'account_code' => 'sometimes|string|unique:accounts,account_code',
            'classification' => [
                'sometimes',
                'string',
            ],
            'name' => 'sometimes|array',
            'name.*' => 'string|sometimes',
            'activity' => 'sometimes',
            'parent_id' => 'sometimes|exists:accounts,id',
            'show_in_expense_claims' => 'sometimes',
            'bank_id' => 'sometimes',
            'is_bank' => 'sometimes',
            'is_locked' => 'sometimes',
            'lock_reason' => 'sometimes|string|max:255',
            'is_system' => 'sometimes',
            'is_payment_enabled' => 'sometimes',
        ];
    }
}
