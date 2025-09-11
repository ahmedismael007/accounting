<?php

namespace App\Http\Requests\Tenant\Accounting\Accountants;

use Illuminate\Foundation\Http\FormRequest;

class JournalRequest extends FormRequest
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
            'date' => ['required', 'date'],
            'reference' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'journal_line_items' => ['required', 'array', 'min:1'],

            'journal_line_items.*.account_id' => ['required', 'integer'],
            'journal_line_items.*.description' => ['nullable', 'string'],
            'journal_line_items.*.currency' => ['required', 'string'],
            'journal_line_items.*.exchange_rate' => ['required', 'numeric'],
            'journal_line_items.*.debit' => ['required', 'numeric', 'min:0'],
            'journal_line_items.*.credit' => ['required', 'numeric', 'min:0'],
            'journal_line_items.*.debit_dc' => ['required', 'numeric'],
            'journal_line_items.*.credit_dc' => ['required', 'numeric'],

            'journal_line_items.*.tax_rate_id' => ['nullable', 'numeric', 'min:0'],
            'journal_line_items.*.contact_id' => ['nullable', 'integer'],
            'journal_line_items.*.project_id' => ['nullable', 'integer'],
            'journal_line_items.*.branch_id' => ['nullable', 'integer'],
            'journal_line_items.*.cost_center_id' => ['nullable', 'integer'],
        ];
    }
}
