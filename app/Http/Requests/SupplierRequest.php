<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\DuiValidationRule;
use App\Rules\NitValidationRule;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class SupplierRequest extends FormRequest
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
    public function rules(Request $request)
    {

        return [
            'razon_social_proveedor'     => 'required',
            'nombre_comercial_proveedor' => 'required',
            'dui_proveedor'              => ['required',new DuiValidationRule,'unique:proveedor,dui_proveedor,' . $request->input("id_proveedor") . ',id_proveedor',],
            'nit_proveedor'              => ['nullable', 'unique:proveedor,nit_proveedor,' . $request->input("id_proveedor") . ',id_proveedor'],
            'id_tipo_contribuyente'      => 'required',
            'id_sujeto_retencion'        => 'required',
            'id_municipio'               => 'required',
            'id_giro'                    => 'required',
        ];
    }

    public function messages()
    {
        return [
            'razon_social_proveedor.required'     => 'El campos Razon social es requerido',
            'nombre_comercial_proveedor.required' => 'El campos Nombre comercial es requerido',
            'dui_proveedor.required'              => 'El campos Dui es requerido',
            'dui_proveedor.unique'                => 'El formato del correspondiente Dui son correctos pero ya exite',
            'nit_proveedor.unique'                => 'El formato del correspondiente Nit son correctos pero ya exite',
            'id_tipo_contribuyente.required'      => 'El campos tipo de contribuyente es requerido',
            'id_sujeto_retencion.required'        => 'El campos sujeto de rentencion es requerido',
            'id_municipio.required'               => 'El campos municipio es requerido',
            'id_giro.required'                    => 'El campos giro es requerido',

        ];
    }
}