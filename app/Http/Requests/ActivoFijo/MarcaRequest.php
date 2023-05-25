<?php

namespace App\Http\Requests\ActivoFijo;

use Illuminate\Foundation\Http\FormRequest;

class MarcaRequest extends FormRequest
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
            'name_brand' => ['required', 'unique:marca,nombre_marca,' . $this->id_brand . ',id_marca'],
        ];
    }
    public function messages()
    {
        return [
            'name_brand.required' => 'Debe escribir el nombre de la marca.',
            'name_brand.unique' => 'Esta marca ya fue registrada.',
        ];
    }
}
