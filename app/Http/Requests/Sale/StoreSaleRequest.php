<?php

namespace App\Http\Requests\Sale;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
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
            'sale_no' => 'nullable|string',
            'sale_date' => 'required|date',
            'user_id' =>  'sometimes|required|exists:users,id',
            'product_id' =>  'required|exists:products,id',
            'quantity' => 'required',
            'rate' => 'required',
            'amount' => 'required',

        ];
    }


    protected function prepareForValidation()
    {
        $fieldsToNormalize = [
            'saleNo' => 'sale_no',
            'saleDate' => 'sale_date',
            'userId' => 'user_id',
            'productId' => 'product_id',
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
