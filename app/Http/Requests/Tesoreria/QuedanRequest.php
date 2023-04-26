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
                if (!empty($detalle[7])) { // Validar si el campo 7 está lleno
                    $rules["detalle_quedan.{$key}.4"] = [
                        Rule::unique('detalle_quedan', 'numero_acta_det_quedan')
                            ->where(function ($query) use ($detalle) {
                                return $query->where('id_dependencia', $detalle[3])
                                    ->where('numero_acta_det_quedan', $detalle[4]);
                            })
                            ->ignore($detalle[1], 'id_det_quedan')
                    ];
                }
            });
        return $rules;
    }

    public function messages()
    {
        $messages = [];


        foreach ($this->input('detalle_quedan', []) as $key => $value) {
            if ($this->input("detalle_quedan.{$key}.7")) {
                $messages["detalle_quedan.{$key}.4.unique"] = '1';
            }
        }

        return $messages;
    }
}
