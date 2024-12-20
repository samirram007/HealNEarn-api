<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'parent_id' => 'required|exists:users,id',
            'manager_id' => 'required|exists:users,id',

            'user_type' => ['required', 'string', 'max:255', 'in:member'],
            'email' => 'nullable|email', // Email is optional
            'contact_no' => 'nullable|string',
            'password' => [
                'nullable',
                Password::min(8)->letters(),
            ],
        ];
    }

    protected function prepareForValidation()
    {
        $fieldsToNormalize = [
            'parentId' => 'parent_id',
            'managerId' => 'manager_id',
            'contactNo' => 'contact_no',
            'userType' => 'user_type',
        ];

        $normalizedData = [];
        foreach ($fieldsToNormalize as $originalKey => $normalizedKey) {
            if ($this->input($originalKey) !== null) { // Check if input exists
                $normalizedData[$normalizedKey] = $this->input($originalKey);
            }
        }

        $this->merge($normalizedData); // Merge normalized fields into request

    }
}
