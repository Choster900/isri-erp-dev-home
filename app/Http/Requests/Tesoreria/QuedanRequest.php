<?php

namespace App\Http\Requests\Tesoreria;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

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
    public function rules(Request $request)
    {

        $rules = [];
        collect($this->input('detalle_quedan', [])) // Convertir el arreglo en una colección
            ->each(function ($detalle, $key) use (&$rules) { // Iterar sobre cada detalle
                if (!empty($detalle["isDelete"])) { // Validar si el campo 7 está lleno
                    $rules["detalle_quedan.{$key}.id_dependencia"] = ['required'];

                    $rules["detalle_quedan.{$key}.numero_acta_det_quedan"] = [
                        Rule::unique('detalle_quedan', 'numero_acta_det_quedan')->where(function ($query) use ($detalle) {
                                return $query->where('id_dependencia', $detalle["id_dependencia"])->where('numero_acta_det_quedan', $detalle["numero_acta_det_quedan"]);
                            })->ignore($detalle["id_det_quedan"], 'id_det_quedan')
                    ];
                }
            });
        return $rules;
    }

    public function messages()
    {
        $messages = [];
        foreach ( $this->input('detalle_quedan', []) as $key => $value ) {
            if ($this->input("detalle_quedan.{$key}.isDelete")) {
                $messages["detalle_quedan.{$key}.id_dependencia.required"] = 'LA DEPENDENCIA ES UN DATO REQUERIDO';
            }
        }

        foreach ( $this->input('detalle_quedan', []) as $key => $value ) {
            if ($this->input("detalle_quedan.{$key}.isDelete")) {
                $messages["detalle_quedan.{$key}.numero_acta_det_quedan.unique"] = '1';
            }
        }


        return $messages;
    }
}