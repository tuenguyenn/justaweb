<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueAmountFromArray implements ValidationRule
{
    /**
     * Thực hiện kiểm tra.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  \Closure(string): void  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        
      


        // Kiểm tra tính duy nhất của mảng
        if (count($value) !== count(array_unique($value))) {
            $fail('Các đơn giá không được trùng nhau.');
        }
    }
}
