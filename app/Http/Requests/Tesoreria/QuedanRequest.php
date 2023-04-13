<?php

namespace App\Http\Requests\Tesoreria;

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
        foreach ( $this->input('detalle_quedan', []) as $key => $value ) {
            if ($this->input("detalle_quedan.{$key}.7")) {
                $rules["detalle_quedan.{$key}.4"] = ['unique:detalle_quedan,numero_acta_det_quedan,' . $this->input("detalle_quedan.{$key}.1") . ',id_det_quedan'];
            }
        }
        return $rules;

    }

    public function messages()
    {
        $messages = [];


        foreach ( $this->input('detalle_quedan', []) as $key => $value ) {
            if ($this->input("detalle_quedan.{$key}.7")) {
                $messages["detalle_quedan.{$key}.4.unique"] = '1';
            }

        }

        return $messages;

    }
}