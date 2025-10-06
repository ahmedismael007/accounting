<?php

namespace App\Http\Requests\Tenant\Payroll;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PayrunRequest extends FormRequest
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
        $isUpdate = in_array($this->method(), ['PUT', 'PATCH']);

        return [
            'start_date' => [$isUpdate ? 'sometimes' : 'required', 'date'],
            'currency' => [$isUpdate ? 'sometimes' : 'required', 'string', 'size:3'],
            'description' => ['sometimes', 'nullable', 'string', 'max:1000'],

            'amount_to_pay' => [$isUpdate ? 'sometimes' : 'required', 'nullable', 'numeric', 'min:0'],

            'employee_id' => [$isUpdate ? 'sometimes' : 'required', 'nullable', 'exists:employees,id'],
            'account_id' => [$isUpdate ? 'sometimes' : 'required', 'exists:accounts,id'],
            'cost_center_id' => ['sometimes', 'nullable', 'exists:cost_centers,id'],
            'project_id' => ['sometimes', 'nullable', 'exists:projects,id'],
            'branch_id' => ['sometimes', 'nullable', 'exists:branches,id'],

            'status' => [$isUpdate ? 'sometimes' : 'required', Rule::in(['DRAFT', 'APPROVED', 'PAID', 'PARTIALLY_PAID'])],
        ];
    }
}
