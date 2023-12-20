<?php

namespace App\Http\Requests\RRHH;

use Illuminate\Foundation\Http\FormRequest;

class PermisoRequest extends FormRequest
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
            'employeeId' => 'required',
            'jobPositionId' => 'required',
            'typeOfPermissionId' => 'required',
            'startDate' => 'required',
            'endDate' => 'required_if:periodOfTime,2',
            'permissionReasonId' => 'required_if:typeOfPermissionId,6',
            'destination' => 'required_if:typeOfPermissionId,5',
            'comingBack' => [
                function ($attribute, $value, $fail) {
                    if ($value == "" && $this->input('periodOfTime') == 1 && $this->input('typeOfPermissionId') == 5) {
                        $fail('Debe seleccionar.');
                    }
                },
            ],
            'startTime' => [
                function ($attribute, $value, $fail) {
                    if (empty($value) && $this->input('periodOfTime') == 1 && empty($this->input('endTime'))) {
                        $fail('Esta hora es obligatoria.');
                    }
                },
            ],
            'endTime' => [
                function ($attribute, $value, $fail) {
                    if (empty($value) && $this->input('typeOfPermissionId') != 5 && $this->input('periodOfTime') == 1 && empty($this->input('startTime'))) {
                        $fail('Esta hora es obligatoria.');
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'employeeId.required'                       => 'Debe seleccionar empleado',
            'jobPositionId.required'                    => 'Debe seleccionar el puesto.',
            'typeOfPermissionId.required'               => 'Debe seleccionar el tipo de permiso.',
            'startDate.required'                        => 'Debe seleccionar fecha inicio.',
            'endDate.required_if'                       => 'Debe seleccionar esta fecha.',
            'permissionReasonId.required_if'            => 'Debe seleccionar el motivo.',
            'destination.required_if'                   => 'Debe escribir el destino.',
        ];
    }
}
