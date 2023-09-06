<?php

namespace App\Http\Requests\RRHH;

use Illuminate\Foundation\Http\FormRequest;

class EvaluacionRequest extends FormRequest
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
        return [
            'id_empleado'    => 'required',
            'fecha_evaluacion_personal'  => 'required',
            'periodo_evaluacion_personal'   => 'required',
        ];
    }
}
