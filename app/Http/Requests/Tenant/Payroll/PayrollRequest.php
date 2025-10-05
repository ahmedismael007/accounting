<?php

namespace App\Http\Requests\Tenant\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class PayrollRequest extends FormRequest
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
         if ($this->isMethod('post')) {
            return [
                'employee_id' => ['required', 'integer'],
                'description' => ['nullable', 'string', 'max:255'],
                'account_id' => ['required', 'integer'],
                'amount' => ['required', 'numeric', 'min:0'],
                'cost_center_id' => ['nullable', 'integer'],
                'total' => ['required', 'numeric', 'min:0'],
                'currency' => ['required', 'string', 'max:10'],
                'project_id' => ['nullable', 'integer'],
                'branch_id' => ['nullable', 'integer'],
                'include_in_payrun' => ['boolean'],
            ];
        }

         if ($this->isMethod('put') || $this->isMethod('patch')) {
            return [
                'employee_id' => ['sometimes', 'required', 'integer'],
                'description' => ['sometimes', 'nullable', 'string', 'max:255'],
                'account_id' => ['sometimes', 'required', 'integer'],
                'amount' => ['sometimes', 'required', 'numeric', 'min:0'],
                'cost_center_id' => ['sometimes', 'nullable', 'integer'],
                'total' => ['sometimes', 'required', 'numeric', 'min:0'],
                'currency' => ['sometimes', 'required', 'string', 'max:10'],
                'project_id' => ['sometimes', 'nullable', 'integer'],
                'branch_id' => ['sometimes', 'nullable', 'integer'],
                'include_in_payrun' => ['sometimes', 'boolean'],
            ];
        }

        return [];
    }
}
