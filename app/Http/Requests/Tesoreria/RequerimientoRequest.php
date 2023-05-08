<?php

namespace App\Http\Requests\Tesoreria;

use App\Rules\UniqueNumeroRequerimientoRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RequerimientoRequest extends FormRequest
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
        $year = date('Y');
        return [
            'numero_requerimiento_pago' => [
                'required',
                Rule::unique('requerimiento_pago','numero_requerimiento_pago')->where(function ($query) use ($year) {
                    return $query->where('anio_requerimiento_pago', $year);
                })
                ->ignore($this->input('id_requerimiento_pago'), 'id_requerimiento_pago')
            ],
            'monto_requerimiento_pago' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'numero_requerimiento_pago.required'     => 'El campo es requerido.',
            'numero_requerimiento_pago.unique'     => 'El número ingresado ya existe para este año.',
            'monto_requerimiento_pago.required'     => 'El campo es requerido.',
        ];
    }

}