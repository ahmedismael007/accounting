<?php

namespace App\Http\Requests\Tenant\Accounting\Accountants;

use App\Enums\TaxType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaxRateRequest extends FormRequest
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
            'name_ar' => 'nullable|string',
            'name_en' => 'nullable|string',
            'tax_type' => ['required', 'string', Rule::in(array_column(TaxType::cases(), 'name'))],
            'tax_rate' => 'numeric|required',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name_ar.string'     => 'يجب أن يكون اسم الضريبة باللغة العربية نصًا.',

            'name_en.string'     => 'يجب أن يكون اسم الضريبة باللغة الإنجليزية نصًا.',

            'tax_type.required'  => 'نوع الضريبة مطلوب.',
            'tax_type.string'    => 'يجب أن يكون نوع الضريبة نصًا.',
            'tax_type.in'        => 'نوع الضريبة غير صالح.',

            'tax_rate.required'  => 'المعدل الضريبي مطلوب.',
            'tax_rate.numeric'   => 'يجب أن يكون المعدل الضريبي رقماً.',

            'description.string' => 'يجب أن يكون الوصف نصًا.',
        ];
    }
}
