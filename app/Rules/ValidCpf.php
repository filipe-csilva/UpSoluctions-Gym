<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ValidCpf implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cpf = preg_replace('/\D/', '', (string) $value);

        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            $fail('O CPF informado é inválido.');

            return;
        }

        $firstDigit = $this->calculateDigit(substr($cpf, 0, 9));
        $secondDigit = $this->calculateDigit(substr($cpf, 0, 9).$firstDigit);

        if ($cpf !== substr($cpf, 0, 9).$firstDigit.$secondDigit) {
            $fail('O CPF informado é inválido.');
        }
    }

    private function calculateDigit(string $digits): int
    {
        $weight = strlen($digits) + 1;
        $sum = 0;

        foreach (str_split($digits) as $digit) {
            $sum += (int) $digit * $weight--;
        }

        $remainder = $sum % 11;

        return $remainder < 2 ? 0 : 11 - $remainder;
    }
}
