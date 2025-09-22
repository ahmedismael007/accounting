<?php

namespace App\Http\Requests\Tenant\Accounting\Branches;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
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
            'name_ar' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'commercial_number' => 'nullable|string|max:50',
            'building_number' => 'nullable|string',
            'street' => 'nullable|string',
            'district' => 'nullable|string',
            'city' => 'nullable|string',
            'postal_code' => 'nullable|string'
        ];
    }
}
