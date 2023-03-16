<?php

namespace App\Http\Requests\ActivoFijo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ModeloRequest extends FormRequest
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
            'id_brand' => ['required'],
            'name_model' => ['required', 'unique:modelo,nombre_modelo,' . $this->id_model . ',id_modelo'],
        ];
    }
    public function messages()
    {
        return [
            'id_brand.required' => 'Debe seleccionar marca.',
            'name_model.required' => 'Debe escribir el nombre del modelo.',
            'name_model.unique' => 'Este nombre de modelo ya fue registrado.',
        ];
    }
}
