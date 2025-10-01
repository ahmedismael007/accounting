<?php

namespace App\Http\Requests\Tenant\Accounting\Branches;

use Illuminate\Foundation\Http\FormRequest;

class CreateBranchRequest extends FormRequest
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
            'name' => 'required|array',
            'name.*' => 'string|max:255',
            'phone' => 'sometimes|string|max:20',
            'commercial_number' => 'sometimes|string|max:50',
            'building_number' => 'sometimes|string',
            'street' => 'sometimes|string',
            'district' => 'sometimes|string',
            'city' => 'sometimes|string',
            'postal_code' => 'sometimes|string'
        ];
    }
}
