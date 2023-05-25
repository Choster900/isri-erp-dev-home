<?php

namespace App\Http\Requests\ActivoFijo;

use Illuminate\Foundation\Http\FormRequest;

class BienEspecificoRequest extends FormRequest
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
            'name' => ['required', 'unique:bien_especifico,nombre_bien_especifico,' . $this->id . ',id_bien_especifico'],
            'budget_account_id' => ['required'],
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Debe escribir el nombre del bien.',
            'name_brand.unique' => 'Esta bien ya fue registrado.',
            'budget_account_id.required' => 'Debe seleccionar especifico.',
        ];
    }
}
