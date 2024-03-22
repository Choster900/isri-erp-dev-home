<?php

namespace App\Http\Requests\UCP;

use Illuminate\Foundation\Http\FormRequest;

class ProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                  => ['required'],
            'description'           => ['required'],
            'mUnitId'               => ['required'],
            'price'                 => ['required'],
            'budgetAccountId'       => ['required'],
            'purchaseProcedureId'   => ['required'],
            'unspscId'              => ['required'],
            'catPercId'             => ['required'],
            'catNicspId'            => ['required'],
            'perishable'            => ['not_in:-1'],
            'gAndS'                 => ['not_in:-1'],

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Debe digitar el nombre del producto.',
            'description.required' => 'Debe escribir la descripción del producto.',
            'mUnitId.required' => 'Debe seleccionar la unidad de medida.',
            'price.required' => 'Debe digitar el precio de referencia.',
            'budgetAccountId.required' => 'Debe seleccionar el especifico.',
            'purchaseProcedureId.required' => 'Debe seleccionar el proceso de compra.',
            'unspscId.required' => 'Debe seleccionar catalogo Unspsc.',
            'catPercId.required' => 'Debe seleccionar codigo perc.',
            'catNicspId.required' => 'Debe seleccionar nicsp.',
            'perishable.not_in' => 'Debe seleccionar si es perecedero o no.',
            'gAndS.not_in' => 'Debe seleccionar si el producto es necesario en el cuadro de bienes o no.',
        ];
    }
}
