<?php

    namespace App\Http\Requests\Central\User;

    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Validation\Rule;

    class UpdateProfileRequest extends FormRequest
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
            $user_id = $this->user()->id;

            return [
                'first_name' => ['sometimes', 'string', 'max:255'],
                'last_name' => ['sometimes', 'string', 'max:255'],
                'email' => ['sometimes', 'email', Rule::unique('users', 'email')->ignore($user_id)],
                'phone' => ['sometimes', 'string', Rule::unique('users', 'phone')->ignore($user_id)],
                'password' => ['sometimes', 'min:6'],
                'avatar' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            ];
        }
    }
