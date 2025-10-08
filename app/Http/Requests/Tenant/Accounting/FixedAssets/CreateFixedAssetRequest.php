<?php

namespace App\Http\Requests\Tenant\Accounting\FixedAssets;

use Illuminate\Foundation\Http\FormRequest;

class CreateFixedAssetRequest extends FormRequest
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
            'asset_number' => ['required', 'string', 'max:255', 'unique:fixed_assets,asset_number'],
            'name' => ['required', 'string', 'max:255'],
            'purchase_date' => ['required', 'date'],
            'purchase_cost' => ['required', 'numeric', 'min:0'],
            'salvage_value' => ['nullable', 'numeric', 'min:0'],
            'life_in_months' => ['required', 'integer', 'min:1'],
            'depreciation_start_month' => ['required', 'date'],
            'current_book_value' => ['nullable', 'numeric', 'min:0'],
            'depreciated_until' => ['nullable', 'date'],
            'fixed_asset_account' => ['required', 'integer',],
            'depreciation_account' => ['required', 'integer',],
            'accumulated_depreciation_account' => ['required', 'integer',],
            'notes' => ['nullable', 'string'],
            'cost_center_id' => ['nullable', 'exists:cost_centers,id'],
            'project_id' => ['nullable', 'exists:projects,id'],
        ];
    }
}
