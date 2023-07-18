<?php

namespace App\Http\Requests\Tesoreria;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class IncomeReceiptRequest extends FormRequest
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
        $rules = [
            'budget_account_id' => ['required'],
        ];
        $rules["client"] = ['required'];
        $rules["treasury_clerk_id"] = ['required'];
        $rules["budget_account_id"] = ['required'];
        $rules["number"] = ['required','unique:recibo_ingreso,numero_recibo_ingreso,' . $request->input("income_receipt_id") . ',id_recibo_ingreso'];
        $rules["description"] = ['required'];
        $rules["financing_source_id"] = ['required'];
        $rules["direction"] = ['required_if:budget_account_id,16304'];
        $rules["document"] = ['required_if:budget_account_id,16304'];
        foreach ($this->input('income_detail', []) as $key => $income_detail) {
            $rules["income_detail.{$key}.income_concept_id"] = [
                function ($attribute, $value, $fail) use ($key, $income_detail) {
                    if (!$income_detail['deleted'] && empty($value)) {
                        $fail("Debe seleccionar el concepto.");
                    }
                }
            ];
            $rules["income_detail.{$key}.amount"] = [
                function ($attribute, $value, $fail) use ($key, $income_detail) {
                    if (!$income_detail['deleted'] && empty($value)) {
                        $fail("Debe ingresar el monto.");
                    }
                }
            ];
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [];   
        $messages["client.required"] = "Debe ingresar el nombre o razón social.";
        $messages["number.required"] = "Debe ingresar el numero del recibo.";
        $messages["number.unique"] = "Este numero de recibo ya ha sido registrado.";
        $messages["budget_account_id.required"] = "Debe seleccionar el especifico presupuestario.";
        $messages["description.required"] = "Debe ingresar la descripción del recibo.";
        $messages["treasury_clerk_id.required"] = "Debe seleccionar Tesorero.";
        $messages["direction.required_if"] = "Debe ingresar la direccion de la persona.";
        $messages["document.required_if"] = "Debe ingresar el documento de identificación de la persona.";
        $messages["financing_source_id.required"] = "Debe seleccionar fuente de financiamiento.";
        return $messages;
    }
}
