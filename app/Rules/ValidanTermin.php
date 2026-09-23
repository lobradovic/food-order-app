<?php

namespace App\Rules;

use Closure;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ValidanTermin implements ValidationRule
{
    private const DOZVOLJENI_TERMINI = [9, 10, 11, 12, 13, 14,15,16,17,18,19,20,21,22,23,24,0,1,2,3,4,5,6,7,8];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!in_array((int) $value, self::DOZVOLJENI_TERMINI, true)) {
            $fail('Nevalidan termin. Dozvoljeni termini su 9, 10, 11, 12, 13 ili 14 časova.');
            return;
        }

        $krajnjiRok = Carbon::today()->setTime((int) $value, 0)->subHour();

        if (Carbon::now()->greaterThan($krajnjiRok)) {
            $fail("Termin u {$value}:00 više nije dostupan — poslednji trenutak za prijavu bio je {$krajnjiRok->format('H:i')}.");
        }
    }
}
