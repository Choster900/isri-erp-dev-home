<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Personas;

class DuiValidationRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ((bool) preg_match('/(^\d{8})-(\d$)/', $value) === true) {
            [$digits, $digit_veri] = explode('-', $value);
            $sum = 0;
            for ($i = 0, $l = strlen($digits); $i < $l; $i++) {
                $sum += (9 - $i) * (int) $digits[$i];
            }
            return (int) $digit_veri === ((10 - ($sum % 10)) % 10);
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El DUI que desea registrar el FALSO';
    }
}