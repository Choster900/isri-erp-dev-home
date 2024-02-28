<?php

namespace App\Http\Requests\UCP;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class BienesServiciosRequest extends FormRequest
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
        $rules = [];
        $rules = [
            'idDetDocAdquisicion' => ['required'],
        ];
        collect($this->input('productAdq', []))
            ->each(function ($productoAdq, $key) use (&$rules) {
                $rules["productAdq.{$key}.idLt"] = ['required'];
                collect($this->input("productAdq.{$key}.detalleDoc", []))
                    ->each(function ($det, $indice) use (&$rules, $key) {
                        $rules["productAdq.{$key}.detalleDoc.{$indice}.idMarca"] = ['required'];
                        $rules["productAdq.{$key}.detalleDoc.{$indice}.idProducto"] = ['required'];
                        $rules["productAdq.{$key}.detalleDoc.{$indice}.idCentroAtencion"] = ['required'];
                        $rules["productAdq.{$key}.detalleDoc.{$indice}.cantProdAdquisicion"] = ['required'];
                        $rules["productAdq.{$key}.detalleDoc.{$indice}.costoProdAdquisicion"] = ['required'];
                    });
            });

        return $rules;
    }
    public function messages()
    {
        $msg = [];
        foreach ($this->input('productAdq', []) as $key => $value) {
            $msg["productAdq.{$key}.idLt.required"] = 'La línea de trabajo es requerida';

            foreach ($this->input("productAdq.{$key}.detalleDoc", []) as $indice => $value) {
                $msg["productAdq.{$key}.detalleDoc.{$indice}.idProducto.required"] = 'El producto es requerido';
                $msg["productAdq.{$key}.detalleDoc.{$indice}.idMarca.required"] = 'La marca es requerida';
                $msg["productAdq.{$key}.detalleDoc.{$indice}.idCentroAtencion.required"] = 'El centro de atención es requerido';
                $msg["productAdq.{$key}.detalleDoc.{$indice}.cantProdAdquisicion.required"] = 'La cantidad de productos es requerida';
                $msg["productAdq.{$key}.detalleDoc.{$indice}.costoProdAdquisicion.required"] = 'El costo de adquisición es requerido';
            }
        }

        return $msg;
    }
}
