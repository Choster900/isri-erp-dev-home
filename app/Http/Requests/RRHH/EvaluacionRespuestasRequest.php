<?php

namespace App\Http\Requests\RRHH;

use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Http\FormRequest;

class EvaluacionRespuestasRequest extends FormRequest
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
        $rules = [];
        collect($this->input('dataIncidenteEvaluacion', []))
            ->each(function ($data, $key) use (&$rules) {
                if (!$data["isDelete"]) {
                    $rules["dataIncidenteEvaluacion.{$key}.comentario_incidente_evaluacion"] = ['required'];
                    $rules["dataIncidenteEvaluacion.{$key}.id_cat_rendimiento"] = ['required'];
                //    $rules["dataIncidenteEvaluacion.{$key}.id_incidente_evaluacion"] = ['required'];
                    $rules["dataIncidenteEvaluacion.{$key}.resultado_incidente_evaluacion"] = ['required'];
                }
            });
        return $rules;
    }
    public function messages()
    {
        /* $messages = [];
        foreach ($this->input('dataIncidenteEvaluacion', []) as $key => $value) {
            $messages["dataIncidenteEvaluacion.{$key}.comentario_incidente_evaluacion.required"] = 'La comentario_incidente_evaluacion es requerido';
            $messages["dataIncidenteEvaluacion.{$key}.id_cat_rendimiento.required"] = 'La required es requerido';
            $messages["dataIncidenteEvaluacion.{$key}.id_incidente_evaluacion.required"] = 'La id_incidente_evaluacion es requerido';
            $messages["dataIncidenteEvaluacion.{$key}.resultado_incidente_evaluacion.required"] = 'La resultado_incidente_evaluacion es requerido';

        }
        return $messages; */

        return [
            'dataIncidenteEvaluacion.*.comentario_incidente_evaluacion.required' => 'El campo comentario_incidente_evaluacion es obligatorio.',
            'dataIncidenteEvaluacion.*.id_cat_rendimiento.required' => 'El campo id_cat_rendimiento es obligatorio.',
            //'dataIncidenteEvaluacion.*.id_incidente_evaluacion.required' => 'El campo id_incidente_evaluacion es obligatorio.',
            'dataIncidenteEvaluacion.*.resultado_incidente_evaluacion.required' => 'El campo resultado_incidente_evaluacion es obligatorio.'
        ];
    }
}
