<?php

namespace App\Http\Requests\Almacen;

use Illuminate\Foundation\Http\FormRequest;

class AjusteEntradaRequest extends FormRequest
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
        $rules["centerId"] = ['required'];
        $rules["idLt"] = 'required_unless:financingSourceId,4';
        $rules["financingSourceId"] = ['required'];
        $rules["reasonId"] = ['required'];
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
            $rules["prods.{$key}.cost"] = [
                function ($attribute, $value, $fail) use ($key, $prod) {
                    if (!$prod['deleted'] && (empty($value) || $value <= 0)) {
                        $fail("Debe ingresar un monto mayor a cero.");
                    }
                }
            ];
            $rules["prods.{$key}.brandId"] = [
                function ($attribute, $value, $fail) use ($key, $prod) {
                    if (!$prod['deleted'] && empty($value)) {
                        $fail("Debe seleccionar marca.");
                    }
                }
            ];
            $rules["prods.{$key}.expDate"] = [
                function ($attribute, $value, $fail) use ($key, $prod) {
                    if (!$prod['deleted'] && (empty($value) && $prod['perishable'] == 1)) {
                        $fail("Debe seleccionar la fecha de caducidad.");
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
        $messages["idLt.required_unless"] = "Debe seleccionar linea de trabajo.";
        $messages["financingSourceId.required"] = "Debe seleccionar fuente de financiamiento.";
        $messages["reasonId.required"] = "Debe seleccionar motivo.";
        return $messages;
    }
}
