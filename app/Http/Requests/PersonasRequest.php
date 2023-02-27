<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonasRequest extends FormRequest
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
            'pnombre_persona'        => 'required',
            'snombre_persona'        => 'required',
            'tnombre_persona'        => 'required',
            'papellido_persona'      => 'required',
            'sapellido_persona'      => 'required',
            'tapellido_persona'      => 'required',
            'telefono_persona'       => 'required',
            'dui_persona'            => 'required',
            'email_persona'          => 'required',
            'id_genero'              => 'required',
            'id_estado_civil'        => 'required',
            'nombre_conyuge_persona' => 'required',
            'nombre_madre_persona'   => 'required',
            'nombre_padre_persona'   => 'required',
            'fecha_nac_persona'      => 'required',
            'id_municipio'           => 'required',
            'id_profesion'           => 'required',
            'id_nivel_educativo'     => 'required',
        ];
    }
    public function messages()
    {
        return [
            'pnombre_persona.required'        => 'El campos campos es requerido ',
            'snombre_persona.required'        => 'El campos campos es requerido ',
            'tnombre_persona.required'        => 'El campos campos es requerido ',
            'papellido_persona.required'      => 'El campos campos es requerido ',
            'sapellido_persona.required'      => 'El campos campos es requerido ',
            'tapellido_persona.required'      => 'El campos campos es requerido ',
            'telefono_persona.required'       => 'El campos campos es requerido ',
            'dui_persona.required'            => 'El campos campos es requerido ',
            'email_persona.required'          => 'El campos campos es requerido ',
            'id_genero.required'              => 'El campos campos es requerido ',
            'id_estado_civil.required'        => 'El campos campos es requerido ',
            'nombre_conyuge_persona.required' => 'El campos campos es requerido ',
            'nombre_madre_persona.required'   => 'El campos campos es requerido ',
            'nombre_padre_persona.required'   => 'El campos campos es requerido ',
            'fecha_nac_persona.required'      => 'El campos campos es requerido ',
            'id_municipio.required'           => 'El campos campos es requerido ',
            'id_profesion.required'           => 'El campos campos es requerido ',
            'id_nivel_educativo.required'     => 'El campos campos es requerido ',
        ];
    }
}