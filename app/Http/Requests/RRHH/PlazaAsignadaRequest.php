<?php

namespace App\Http\Requests\RRHH;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlazaAsignadaRequest extends FormRequest
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
        $rules["jobPosition.dependencyId"] = ['required'];
        $rules["jobPosition.jobPositionId"] = ['required'];
        $rules["jobPosition.salary"] = ['required', 'numeric', 'between:' . $this->lowerSalaryLimit . ',' . $this->upperSalaryLimit];
        $rules["jobPosition.account"] = ['required'];
        $rules["jobPosition.subaccount"] = ['required'];
        $rules["jobPosition.dateOfHired"] = ['required'];

        return $rules;
    }

    public function messages()
    {
        $messages["jobPosition.dependencyId.required"] = "Debe seleccionar la dependencia.";
        $messages["jobPosition.jobPositionId.required"] = "Debe seleccionar el puesto de trabajo.";
        $messages["jobPosition.salary.required"] = "Debe escribir el salario.";
        $messages["jobPosition.account.required"] = "Debe escribir el numero de partida.";
        $messages["jobPosition.subaccount.required"] = "Debe escribir el numero de subpartida.";
        $messages["jobPosition.dateOfHired.required"] = "Debe agregar la fecha de nombramiento.";
        $messages["jobPosition.salary.between"] = "El salario debe estar entre :min y :max.";
        return $messages;
    }
}
