<?php

namespace App\Http\Requests\Tenant\Accounting\FixedAssets;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFixedAssetRequest extends FormRequest
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
        $id = $this->route('fixed_asset');

        return [
            'asset_number' => ['sometimes', 'string', 'max:255', "unique:fixed_assets,asset_number,{$id}"],
            'name' => ['sometimes', 'string', 'max:255'],
            'purchase_date' => ['sometimes', 'date'],
            'purchase_cost' => ['sometimes', 'numeric', 'min:0'],
            'salvage_value' => ['sometimes', 'numeric', 'min:0'],
            'life_in_months' => ['sometimes', 'integer', 'min:1'],
            'depreciation_start_month' => ['sometimes', 'date'],
            'current_book_value' => ['sometimes', 'numeric', 'min:0'],
            'depreciated_until' => ['sometimes', 'date'],
            'fixed_asset_account' => ['sometimes', 'integer'],
            'depreciation_account' => ['sometimes', 'integer'],
            'accumulated_depreciation_account' => ['sometimes', 'integer'],
            'notes' => ['sometimes', 'string'],
            'cost_center_id' => ['sometimes', 'exists:cost_centers,id'],
            'project_id' => ['sometimes', 'exists:projects,id'],
        ];
    }
}
