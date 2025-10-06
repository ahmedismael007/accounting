<?php

namespace App\Http\Requests\Tenant\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class PayrollPaymentRequest extends FormRequest
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
        $isPost = $this->isMethod('post');

        return [
            'account_id' => [$isPost ? 'required' : 'sometimes', 'integer', 'exists:accounts,id'],
            'currency' => [$isPost ? 'required' : 'sometimes', 'string', 'max:4'],
            'amount_paid' => [$isPost ? 'required' : 'sometimes', 'numeric', 'min:0'],
            'date' => [$isPost ? 'required' : 'sometimes', 'date'],
            'description' => ['nullable', 'string', 'max:500'],
            'branch_id' => ['nullable', 'integer', 'exists:branches,id'],
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'cost_center_id' => ['nullable', 'integer', 'exists:cost_centers,id'],
            'reference' => ['nullable', 'string', 'max:100'],
            'adjustment_amount' => ['nullable', 'numeric'],
            'adjustment_account' => ['nullable', 'integer', 'exists:accounts,id'],
        ];
    }
}
