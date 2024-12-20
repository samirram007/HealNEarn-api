<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class UpdateManagerRequest extends FormRequest
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
            'userType' => ['sometimes', 'string', 'max:255'],
            'email' => 'sometimes|email',
            'contactNo' => 'sometimes|',

        ];
    }

    protected function prepareForValidation()
    {
        $fieldsToNormalize = [
            'username' => 'username',
            'userType' => 'user_type',
            'contactNo' => 'contact_no',

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
