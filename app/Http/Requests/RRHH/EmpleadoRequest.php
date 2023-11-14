<?php

namespace App\Http\Requests\RRHH;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\DuiValidationRule;
use Illuminate\Validation\Rule;

class EmpleadoRequest extends FormRequest
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
        $rules["persona.dui_persona"] = ['required',new DuiValidationRule, Rule::unique('persona','dui_persona')
        ->ignore($this->input('persona.id_persona'), 'id_persona')];
        $rules["nup_empleado"] = [Rule::unique('empleado','nup_empleado')->ignore($this->input('id_empleado'), 'id_empleado')];
        $rules["isss_empleado"] = [Rule::unique('empleado','isss_empleado')->ignore($this->input('id_empleado'), 'id_empleado')];
        $rules["codigo_empleado"] = [Rule::unique('empleado','codigo_empleado')->ignore($this->input('id_empleado'), 'id_empleado')];

        return $rules;
    }
    public function messages()
    {
        $messages["persona.dui_persona.unique"] = "Este numero de dui ya fue registrado";
        $messages["persona.dui_persona.required"] = "El numero de dui es requerido";
        $messages["nup_empleado"] = "Este numero nup ya fue registrado";
        $messages["isss_empleado"] = "Este numero isss ya fue registrado";
        $messages["codigo_empleado"] = "Este codigo ya fue registrado.";
        return $messages;
    }
}
