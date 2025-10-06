<?php

namespace App\Http\Requests\Tenant\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
        if ($this->isMethod('PUT')) {
            return [
                'fullname' => 'sometimes|string|between:2,100',
                'email' => 'sometimes|string|email',
                'country' => 'sometimes|string',
                'user_id' => 'sometimes|integer',
            ];
        }

        return [
            'fullname' => 'required|string|between:2,100',
            'email' => 'required|string|email',
            'country' => 'required|string',
            'user_id' => 'required|integer',
        ];
    }
}
