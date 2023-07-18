<?php

namespace App\Http\Requests\RRHH;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class BeneficiariosRequest extends FormRequest
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
    public function rules(Request $request)
    {

        $rules = [];
        $rules = [
            'id_persona' => ['required'],
        ];


        collect($this->input('dataRow', []))->each(function ($familiar, $key) use (&$rules) {
            if (!$familiar["isDelete"]) {
                $rules["dataRow.{$key}.nombre_familiar"] = ['required'];
                $rules["dataRow.{$key}.id_parentesco"] = [
                    'required',
                    Rule::unique('familiar', 'id_parentesco')->where(function ($query) use ($familiar) {
                        return $query->where('id_persona', $this->input('id_persona'))
                            ->where("estado_familiar", 1)->whereIn('id_parentesco', [1, 2, 6]);
                    })->ignore($familiar["id_familiar"], 'id_familiar'),
                ];
                $rules["dataRow.{$key}.porcentaje_familiar"] = ['required', 'min:1'];
            }
        });
        return $rules;

    }
    public function messages()
    {
        $messages = [];
        $messages = [
            'id_persona.required' => 'Debe seleccionar un empleado',
        ];

        foreach ( $this->input('dataRow', []) as $key => $value ) {
            if (!$this->input("dataRow.{$key}.isDelete")) {
                $messages["dataRow.{$key}.nombre_familiar.required"] = 'El nombre es un dato requerido';
                $messages["dataRow.{$key}.id_parentesco.required"] = 'El parentesco es un dato requerido';
                $messages["dataRow.{$key}.id_parentesco.unique"] = 'El Paretesco esta repetido';
                $messages["dataRow.{$key}.porcentaje_familiar.required"] = 'El porcentaje es un dato requerido';
            }
        }
        return $messages;
    }
}