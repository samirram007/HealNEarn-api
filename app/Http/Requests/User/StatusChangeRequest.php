<?php

namespace App\Http\Requests\User;

use App\Enums\UserStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StatusChangeRequest extends FormRequest
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

            'status' => ['required', new Enum(UserStatusEnum::class)],
            'user_id' => ['required', 'exists:users,id'],
            'product_id' => ['sometimes', 'nullable', 'exists:products,id'],
            'product_no' => ['nullable', 'string'],
            'product_amount' => ['nullable'],
            'purchase_date' => ['nullable', 'date'],
        ];
    }

    protected function prepareForValidation()
    {
        $fieldsToNormalize = [
            'userId' => 'user_id',
            'productId' => 'product_id',
            'productNo' => 'product_no',
            'productAmount' => 'product_amount',
            'purchaseDate' => 'purchase_date',
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
