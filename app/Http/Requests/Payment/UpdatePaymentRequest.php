<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
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
                'payment_date' => 'required|date',
                'user_id' =>  'required|exists:users,id',
                'payment_method' => ['required', 'string', 'max:255' ],
                'amount' => 'required',
                'manager_id' => 'required|exists:users,id',
        ];
    }

    protected function prepareForValidation()
    {
        $fieldsToNormalize = [
            'paymentDate' => 'payment_date',
            'userId' => 'user_id',
            'paymentMethod' => 'payment_method',
            'managerId' => 'manager_id',
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
