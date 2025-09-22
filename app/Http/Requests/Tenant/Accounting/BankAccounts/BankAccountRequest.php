<?php

namespace App\Http\Requests\Tenant\Accounting\BankAccounts;

use App\Enums\BankAccountType;
use App\Enums\CurrencyType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BankAccountRequest extends FormRequest
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
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'type' => ['required', 'string', Rule::in(BankAccountType::options())],
            'currency' => ['required', 'string', Rule::in(CurrencyType::options())]
        ];
    }
}
