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
            'idEmpleado'              => 'required',
            'idCentroAtencion'           => 'required',
            'fechaInicioFechafin'     => 'required',
            'idEvaluacionRendimiento' => 'required',
            'idTipoEvaluacion'        => 'required',
            'plazasAsignadas'         => 'required',
        ];
    }

    public function messages()
    {
        return [
            'idEmpleado.required'              => 'Debe seleccionar el empleado.',
            'idCentroAtencion.required'           => 'Debe seleccionar la dependencia.',
            'fechaInicioFechafin.required'     => 'El rango de fechas es requerido.',
            'idEvaluacionRendimiento.required' => 'Debe seleccionar una evaluacion.',
            'idTipoEvaluacion.required'        => 'El tipo de evaluación es requerido.',
            'plazasAsignadas.required'         => 'Debe seleccionar plazas para evaluar.',
        ];
    }
}
