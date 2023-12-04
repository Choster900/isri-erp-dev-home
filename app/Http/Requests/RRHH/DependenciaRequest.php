<?php

namespace App\Http\Requests\RRHH;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DependenciaRequest extends FormRequest
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
        $rules["depName"] = ['required'];
        $rules["personId"] = ['required'];
        $rules["parentId"] = ['required'];
        $rules["code"] = ['required',Rule::unique('dependencia','codigo_dependencia')->ignore($this->input('id'), 'id_dependencia')];
        $rules["email"] = ['nullable', 'email'];
        $rules["phoneNumber"] = ['nullable', 'min:9'];

        return $rules;
    }

    public function messages()
    {
        $messages["code"] = "Este codigo pertenece a otra dependencia.";
        $messages["depName.required"] = "Debe escribir el nombre de la dependencia.";
        $messages["personId.required"] = "Debe seleccionar la persona a cargo";
        $messages["parentId.required"] = "Debe seleccionar la dependencia jerarquica.";
        $messages["code.required"] = "Debe escribir el codigo de la dependencia.";
        $messages["email.email"] = "El formato del email es inválido.";
        $messages["phoneNumber.min"] = "Este numero no contienen la cantidad minima de digitos.";

        return $messages;
    }
}
