<?php

namespace App\Http\Requests\Tesoreria;

use App\Rules\UniqueNumeroRequerimientoRule;
use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'numero_requerimiento_pago' => ['required', new UniqueNumeroRequerimientoRule],
        ];
    }

    public function messages()
    {
        return [
            'numero_requerimiento_pago.required'     => 'El campos es requerido',

        ];
    }

}