<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateSale implements Rule
{
    public function passes($attribute, $value): bool
    {
        // Ensure value is an array with required keys and valid data
        return is_array($value) &&
            isset($value['amount'], $value['sale_date']) &&
            is_numeric($value['amount']) && $value['amount'] >= 0 &&
            strtotime($value['date']) !== false;
    }

    public function message(): string
    {
        return 'The :attribute must be a valid sale object with amount and date.';
    }
}
