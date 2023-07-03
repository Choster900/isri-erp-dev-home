<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\DuiValidationRule;
use Illuminate\Validation\Rule;

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
            'dui_persona'        => ['required', new DuiValidationRule, 
            Rule::unique('persona','dui_persona')
            ->ignore($this->input('id_persona'), 'id_persona')],
            'email_persona'      => ['required', 'email'],
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
            'pnombre_persona.required'    => 'El campo primer nombre es requerido ',
            'snombre_persona.required'    => 'El campo segundo nombre es requerido ',
            'papellido_persona.required'  => 'El campo primer apellido es requerido ',
            'sapellido_persona.required'  => 'El campo segundo nombre es requerido ',
            'telefono_persona.required'   => 'El campo telefono es requerido ',
            'dui_persona.required'        => 'El campo dui es requerido ',
            'email_persona.required'      => 'El campo email es requerido ',
            'email_persona.email'         => 'El correo tiene que ser valido ',
            'dui_persona.unique'          => 'Este numero de DUI ya se encuentra registrado.',
            'id_genero.required'          => 'El campo genero es requerido ',
            'id_estado_civil.required'    => 'El campo estado civil es requerido ',
            'fecha_nac_persona.required'  => 'El campo fecha nacimiento es requerido ',
            'id_municipio.required'       => 'El campo municipio es requerido ',
            'id_profesion.required'       => 'El campo profesión es requerido ',
            'id_nivel_educativo.required' => 'El campo nivel educativo es requerido ',
        ];
    }
}