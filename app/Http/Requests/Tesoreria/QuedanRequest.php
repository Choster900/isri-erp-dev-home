<?php

namespace App\Http\Requests\Tesoreria;

use Illuminate\Foundation\Http\FormRequest;

class QuedanRequest extends FormRequest
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
            'quedan.id_proveedor'                  => 'required',
            'quedan.id_acuerdo_compra'             => 'required',
            'quedan.numero_acuerdo_quedan'         => 'required',
            'quedan.numero_compromiso_ppto_quedan' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'quedan.id_proveedor.required'                  => '1',
            'quedan.id_acuerdo_compra.required'             => '1',
            'quedan.numero_acuerdo_quedan.required'         => '1',
            'quedan.numero_compromiso_ppto_quedan.required' => '1',
            'quedan.numero_compromiso_ppto_quedan.integer'  => '1',
        ];
    }
}
