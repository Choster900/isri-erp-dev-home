<?php

namespace App\Http\Requests\Almacen;

use Illuminate\Foundation\Http\FormRequest;

class AjusteSalidaRequest extends FormRequest
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
        $rules["reasonId"] = ['required'];
        $rules["centerId"] = ['required'];
        $rules["financingSourceId"] = ['required'];
        foreach ($this->input('prods', []) as $key => $prod) {
            $rules["prods.{$key}.detId"] = [
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
        $messages["centerId.required"] = "Debe seleccionar proveedor.";
        $messages["financingSourceId.required"] = "Debe seleccionar fuente de financiamiento.";
        $messages["reasonId.required"] = "Debe seleccionar motivo.";
        return $messages;
    }
}
