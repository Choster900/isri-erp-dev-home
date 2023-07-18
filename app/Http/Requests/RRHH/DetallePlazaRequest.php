<?php

namespace App\Http\Requests\RRHH;

use Illuminate\Foundation\Http\FormRequest;

class DetallePlazaRequest extends FormRequest
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
            'actividad_institucional.linea_trabajo.id_lt'       => 'required',
            'id_proy_financiado'                                => 'required',
            'id_actividad_institucional'                        => 'required',
            'id_plaza'                                          => 'required',
            'id_tipo_contrato'                                  => 'required',
            'id_estado_plaza'                                   => 'required',        
        ];
    }

    public function messages()
    {
        return [
            'actividad_institucional.linea_trabajo.id_lt'       => 'Debe seleccionar uplt',
            'id_proy_financiado.required'                       => 'Debe seleccionar la fuente de financiamiento.',
            'id_actividad_institucional.required'               => 'Debe seleccionar actividad institucional.',
            'id_plaza.required'                                 => 'Debe seleccionar la plaza.',
            'id_tipo_contrato.required'                         => 'Debe seleccionar el tipo de contratacion.',
            'id_estado_plaza.required'                          => 'Debe seleccionar el estado de la plaza.',
        ];
    }
}
