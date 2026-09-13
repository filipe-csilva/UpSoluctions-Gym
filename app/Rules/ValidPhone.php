<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhone implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phone = preg_replace('/\D/', '', (string) $value);

        if (! in_array(strlen($phone), [10, 11], true) || preg_match('/^(\d)\1+$/', $phone)) {
            $fail('O telefone informado é inválido.');
        }
    }
}
