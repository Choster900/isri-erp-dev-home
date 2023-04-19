<?php

namespace App\Http\Requests\Tesoreria;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class IncomeConceptRequest extends FormRequest
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
            'budget_account_id' => ['required'],
            'name' => ['required', Rule::unique('concepto_ingreso', 'nombre_concepto_ingreso')->where(function ($query) {
                return $query->where('id_dependencia', $this->dependency_id);
            })->ignore($this->income_concept_id, 'id_concepto_ingreso')],
            'financing_source_id' => ['required'],
        ];
    }
    public function messages()
    {
        return [
            'budget_account_id.required' => 'Debe seleccionar el especifico presupuestario.',
            'name.required' => 'Debe escribir el nombre del concepto de ingreso.',
            'name.unique' => 'Este concepto de ingreso ya ha sido registrado para el centro seleccionado.',
            'financing_source_id.required' => 'Debe seleccionar fuente de financiamiento.',
        ];
    }
}
