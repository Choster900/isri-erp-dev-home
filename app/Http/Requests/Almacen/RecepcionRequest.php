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
        $rules["invoice"] = ['required'];
        foreach ($this->input('prods', []) as $key => $prod) {
            $rules["prods.{$key}.prodId"] = [
                function ($attribute, $value, $fail) use ($key, $prod) {
                    if (!$prod['deleted'] && empty($value)) {
                        $fail("Debe seleccionar el producto.");
                    }
                }
            ];
            $rules["prods.{$key}.qty"] = [
                function ($attribute, $value, $fail) use ($key, $prod) {
                    if (!$prod['deleted'] && (empty($value) || $value <= 0)) {
                        $fail("Debe ingresar la cantidad de productos.");
                    }
                }
            ];
            $rules["prods.{$key}.expiryDate"] = [
                function ($attribute, $value, $fail) use ($key, $prod) {
                    if (!$prod['deleted'] && (empty($value) && $prod['perishable'] == 1)) {
                        $fail("Debe seleccionar la fecha de caducidad.");
                    }
                }
            ];
            $rules["prods.{$key}.avails"] = [
                function ($attribute, $value, $fail) use ($key, $prod) {
                    if (!$prod['deleted'] && ($value < 0)) {
                        $fail("Ha excedido la cantidad de productos.");
                    }
                }
            ];
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [];   
        $messages["invoice.required"] = "Debe ingresar factura.";
        return $messages;
    }
}
