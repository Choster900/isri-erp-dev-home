<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\DuiValidationRule;
use App\Rules\EmailValidatorRule;

class PersonaRequest extends FormRequest
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
            'pnombre_persona'    => 'required',
            'snombre_persona'    => 'required',
            'papellido_persona'  => 'required',
            'sapellido_persona'  => 'required',
            'telefono_persona'   => 'required',
            'dui_persona'        => ['required', new DuiValidationRule],
            'email_persona'      => ['required','email'],
            'id_genero'          => 'required',
            'id_estado_civil'    => 'required',
            'fecha_nac_persona'  => 'required',
            'id_municipio'       => 'required',
            'id_profesion'       => 'required',
            'id_nivel_educativo' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'pnombre_persona.required'    => 'El campos primer nombre es requerido ',
            'snombre_persona.required'    => 'El campos segundo nombre es requerido ',
            'papellido_persona.required'  => 'El campos primer apellido es requerido ',
            'sapellido_persona.required'  => 'El campos segundo nombre es requerido ',
            'telefono_persona.required'   => 'El campos telefono es requerido ',
            'dui_persona.required'        => 'El campos dui es requerido ',
            'email_persona.required'      => 'El campos email es requerido ',
            'email_persona.email'      => 'El correo tiene que ser valido ',
            'id_genero.required'          => 'El campos genero es requerido ',
            'id_estado_civil.required'    => 'El campos estado civil es requerido ',
            'fecha_nac_persona.required'  => 'El campos fecha nacimiento es requerido ',
            'id_municipio.required'       => 'El campos municipio es requerido ',
            'id_profesion.required'       => 'El campos profesión es requerido ',
            'id_nivel_educativo.required' => 'El campos nivel educativo es requerido ',
        ];
    }
}