<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NitValidationRule implements Rule
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

        $nit = str_replace(["-", " "], "", $value);

        // Asegurarse de que el NIT tenga 14 caracteres
        if (strlen($value) != 14) {
            return false;
        }

        // Asegurarse de que el primer dígito sea 0, 1, 2 o 3
        if (!in_array($nit[0], ["0", "1", "2", "3"])) {
            return false;
        }

        // Validar el último dígito verificador
        $suma = 0;
        for ($i = 0; $i < 13; $i++) {
            $peso = ($i % 6) + 2;
            $suma += intval($nit[$i]) * $peso;
        }
        $resto = $suma % 11;
        $resultado = $resto > 1 ? 11 - $resto : 0;
        if ($nit[13] != strval($resultado)) {
            return false;
        }

        // Si ha pasado todas las validaciones, entonces el NIT es válido
        return true;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El Nit correspondiente tiene el formato incorrecto.';
    }
}