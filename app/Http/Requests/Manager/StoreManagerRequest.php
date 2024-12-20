<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreManagerRequest extends FormRequest
{
    /**
     * Determine if the manager is authorized to make this request.
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
            'user_type' => ['required', 'string', 'max:255', 'in:manager'],
            'email' => 'sometimes|nullable|email',
            'contact_no' => 'sometimes|nullable ',
            'parent_id' => 'required|exists:users,id',
            'password' => [
                'nullable',
                Password::min(8)
                    ->letters(),
            ],
        ];
    }

    protected function prepareForValidation()
    {
        $fieldsToNormalize = [
            'username' => 'username',
            'userType' => 'user_type',
            'contactNo' => 'contact_no',
            'parentId' => 'parent_id',
            'password' => 'password',
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
