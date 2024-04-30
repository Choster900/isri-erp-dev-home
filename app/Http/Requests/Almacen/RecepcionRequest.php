<?php

namespace App\Http\Requests\Almacen;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class RecepcionRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $rules = [];

        foreach ($this->input('prods', []) as $key => $prodGroup) {
            foreach ($prodGroup['productos'] as $prodKey => $prod) {
                $groupKey = "{$key}.productos.{$prodKey}";

                $rules["prods.{$groupKey}.prodId"] = [
                    function ($attribute, $value, $fail) use ($prodKey, $prod) {
                        if (!$prod['deleted'] && empty($value)) {
                            $fail("Debe seleccionar el producto.");
                        }
                    }
                ];

                $rules["prods.{$groupKey}.qty"] = [
                    function ($attribute, $value, $fail) use ($prodKey, $prod) {
                        if (!$prod['deleted'] && (empty($value) || $value <= 0)) {
                            $fail("Debe ingresar la cantidad de productos.");
                        }
                    }
                ];

                $rules["prods.{$groupKey}.brandId"] = [
                    function ($attribute, $value, $fail) use ($prodKey, $prod) {
                        if (!$prod['deleted'] && empty($value)) {
                            $fail("Debe seleccionar marca.");
                        }
                    }
                ];

                $rules["prods.{$groupKey}.expiryDate"] = [
                    function ($attribute, $value, $fail) use ($prodKey, $prod) {
                        if (!$prod['deleted'] && (empty($value) && $prod['perishable'] == 1)) {
                            $fail("Debe seleccionar la fecha de caducidad.");
                        }
                    }
                ];

                $rules["prods.{$groupKey}.avails"] = [
                    function ($attribute, $value, $fail) use ($prodKey, $prod) {
                        if (!$prod['deleted'] && ($value < 0)) {
                            $fail("Ha excedido la cantidad de productos.");
                        }
                    }
                ];

                $rules["prods.{$groupKey}.total"] = [
                    function ($attribute, $value, $fail) use ($prodKey, $prod) {
                        if (!$prod['deleted'] && ($value < 0) && ($this->input('isGas', false))) {
                            $fail("Debe digitar monto.");
                        }
                    }
                ];
            }
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];
        return $messages;
    }
}
