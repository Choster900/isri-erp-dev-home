<?php

namespace App\Http\Requests\RRHH;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $rules["id_puesto_sirhi_det_plaza"] = ['required',Rule::unique('detalle_plaza','id_puesto_sirhi_det_plaza')->ignore($this->input('id_det_plaza'), 'id_det_plaza')];
        $rules["actividad_institucional.linea_trabajo.id_lt"] = ['required'];
        $rules["id_proy_financiado"] = ['required'];
        $rules["id_actividad_institucional"] = ['required'];
        $rules["id_plaza"] = ['required'];
        $rules["id_tipo_contrato"] = ['required'];
        $rules["id_proy_financiado"] = ['required'];

        return $rules;
    }

    public function messages()
    {
        $messages["id_puesto_sirhi_det_plaza"] = "Este codigo ya pertenece a un puesto.";
        $messages["id_puesto_sirhi_det_plaza.required"] = "Debe escribir el codigo para el puesto.";
        $messages["id_actividad_institucional.required"] = "Debe seleccionar actividad institucional.";
        $messages["actividad_institucional.linea_trabajo.id_lt.required"] = "Debe seleccionar linea de trabajo.";
        $messages["id_proy_financiado.required"] = "Debe seleccionar fuente de financiamiento.";
        $messages["id_plaza.required"] = "Debe seleccionar el puesto.";
        $messages["id_tipo_contrato.required"] = "Debe seleccionar tipo de contratación.";
        return $messages;
    }
}
