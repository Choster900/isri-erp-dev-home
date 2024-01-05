<?php

namespace App\Http\Requests\RRHH;

use Illuminate\Foundation\Http\FormRequest;

class IncidentesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $data = $this->input('dataIndiceEvaluacion');

        return [
            $data["resultadoIncidenteEvaluacion"] => ['required'],
            $data["idCategoriaRendimiento"] => ['required'],
            // Asegúrate de agregar otras reglas de validación según sea necesario
        ];
    }
}
