<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UniqueNumeroRequerimientoRule implements Rule
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
        $currentId = request()->route('id_requerimiento_pago'); // Obtener el ID actual del requerimiento de pago en la ruta
        $count = DB::table('requerimiento_pago')
            ->where('numero_requerimiento_pago', $value)
            ->where('anio_requerimiento_pago', Carbon::now()->year)
            ->where('id_requerimiento_pago', '!=', $currentId) // Excluir el ID actual
            ->count();
        return $count === 0; // La regla pasa si el número de requerimiento de pago es único en el año actual

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El número de requerimiento de pago debe ser único en el año actual.';
    }
}