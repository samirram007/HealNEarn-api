<?php

namespace App\Http\Requests\Member;

use App\Http\Requests\Sale\StoreSaleRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreMemberRequest extends FormRequest
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
            'user_type' => ['required', 'string', 'max:255', 'in:member'],
            'email' => 'sometimes|nullable|email',
            'contact_no' => 'sometimes|nullable ',
            'parent_id' => 'required|exists:users,id',
            'manager_id' => 'required|exists:users,id',
            'password' => [
                'nullable',
                Password::min(8)
                    ->letters(),
            ],
            'sale' => ['required', 'array'], // Validate sale as an array/object
            'sale.sale_no' => ['nullable', 'string'], // sale_no must be a string
            'sale.sale_date' => ['required', 'date'], // sale_date must be a valid date
            'sale.product_id' => ['required', 'integer', 'exists:products,id'], // product_id must exist in products table
            'sale.rate' => ['required', 'numeric', 'min:300'], // rate must be a non-negative number
            'sale.quantity' => ['required', 'integer', 'min:1'], // quantity must be an integer >= 1
            'sale.amount' => ['required', 'numeric', 'min:300'], // amount must be a non-negative number

        ];
    }

    protected function prepareForValidation()
    {
        $fieldsToNormalize = [
            'userType' => 'user_type',
            'contactNo' => 'contact_no',
            'parentId' => 'parent_id',
            'managerId' => 'manager_id',
            'sale.saleNo' => 'sale.sale_no',
            'sale.saleDate' => 'sale.sale_date',
            'sale.productId' => 'sale.product_id',
        ];
        $normalizedData = $this->normalizeFields($this->all(), $fieldsToNormalize);

        $this->merge($normalizedData); // Merge normalized fields into request

    }

    /**
 * Normalize fields in a recursive manner.
 *
 * @param array $data
 * @param array $fieldsToNormalize
 * @return array
 */
protected function normalizeFields(array $data, array $fieldsToNormalize): array
{
    foreach ($fieldsToNormalize as $originalKey => $normalizedKey) {
        // Handle nested fields
        if (str_contains($originalKey, '.')) {
            $keys = explode('.', $originalKey);
            $normalizedKeys = explode('.', $normalizedKey);

            // Traverse and normalize nested keys
            $value = $data;
            foreach ($keys as $key) {
                if (!is_array($value) || !array_key_exists($key, $value)) {
                    $value = null;
                    break;
                }
                $value = $value[$key];
            }

            if ($value !== null) {
                $this->setNestedValue($data, $normalizedKeys, $value);
            }
        } else {
            // Normalize top-level fields
            if (array_key_exists($originalKey, $data)) {
                $data[$normalizedKey] = $data[$originalKey];
                unset($data[$originalKey]);
            }
        }
    }

    return $data;
}

/**
 * Set a value for a nested key structure in an array.
 *
 * @param array &$data
 * @param array $keys
 * @param mixed $value
 * @return void
 */
protected function setNestedValue(array &$data, array $keys, $value): void
{
    $current = &$data;
    foreach ($keys as $key) {
        if (!isset($current[$key]) || !is_array($current[$key])) {
            $current[$key] = [];
        }
        $current = &$current[$key];
    }
    $current = $value;
}
}
