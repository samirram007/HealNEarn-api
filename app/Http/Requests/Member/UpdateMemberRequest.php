<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMemberRequest extends FormRequest
{
    /**
     * Determine if the member is authorized to make this request.
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
            'user_ype' => ['nullable', 'string', 'max:255'],
            'email' => 'sometimes|nullable|email',
            'contact_no' => 'sometimes|nullable ',
            'parent_id' => 'required|exists:users,id',
            'manager_id' => 'required|exists:users,id',
        ];
    }

    protected function prepareForValidation()
    {
        $fieldsToNormalize = [
            'username' => 'username',
            'userType' => 'user_type',
            'contactNo' => 'contact_no',
            'parentId' => 'parent_id',
            'managerId' => 'manager_id',
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
