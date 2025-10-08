<?php

namespace App\Http\Requests\Tenant\Accounting\Accountants;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJournalRequest extends FormRequest
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
            'date' => ['sometimes', 'date'],
            'reference' => ['sometimes', 'string'],
            'notes' => ['sometimes', 'string'],
            'account_id' => ['sometimes', 'exists:accounts,id'],

            'journal_line_items' => ['sometimes', 'array', 'min:1'],

            'journal_line_items.*.account_id' => ['sometimes', 'integer'],
            'journal_line_items.*.description' => ['sometimes', 'string'],
            'journal_line_items.*.currency' => ['sometimes', 'string'],
            'journal_line_items.*.exchange_rate' => ['sometimes', 'numeric'],
            'journal_line_items.*.debit' => ['sometimes', 'numeric', 'min:0'],
            'journal_line_items.*.credit' => ['sometimes', 'numeric', 'min:0'],
            'journal_line_items.*.debit_dc' => ['sometimes', 'numeric'],
            'journal_line_items.*.credit_dc' => ['sometimes', 'numeric'],

            'journal_line_items.*.tax_rate_id' => ['sometimes', 'numeric', 'min:0'],
            'journal_line_items.*.contact_id' => ['sometimes', 'integer'],
            'journal_line_items.*.project_id' => ['sometimes', 'integer'],
            'journal_line_items.*.branch_id' => ['sometimes', 'integer'],
            'journal_line_items.*.cost_center_id' => ['sometimes', 'integer'],
        ];
    }
}
