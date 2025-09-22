<?php

namespace App\Http\Requests\Tenant\Accounting\Accountants;

use App\Enums\AccountSubClassificationType;
use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
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
            'account_code' => 'nullable|string|unique:accounts,account_code',
            'classification' => [
                'nullable',
                'string',
            ],
            'name' => 'nullable|array',
            'name.*'=> 'string|nullable',
             'activity' => 'nullable',
            'parent_id' => 'nullable|exists:accounts,id',
            'show_in_expense_claims' => 'nullable',
            'bank_id' => 'nullable',
            'is_bank' => 'nullable',
            'is_locked' => 'nullable',
            'lock_reason' => 'nullable|string|max:255',
            'is_system' => 'nullable',
            'is_payment_enabled' => 'nullable',
        ];
    }
}
