<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class PasswordChangeRequest extends FormRequest
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

            'new_password' => [
                'required',
                'confirmed',
                Password::min(8),
            ],
        ];
    }

    protected function prepareForValidation()
    {
        $fieldsToNormalize = [
            'newPassword' => 'new_password',
            'newPasswordConfirmation' => 'new_password_confirmation',
        ];

        $normalizedData = [];
        foreach ($fieldsToNormalize as $originalKey => $normalizedKey) {
            if ($this->input($originalKey) !== null) { // Check if input exists
                $normalizedData[$normalizedKey] = $this->input($originalKey);
            }
        }
        //  dd($normalizedData);
        $this->merge($normalizedData); // Merge normalized fields into request

    }
}
