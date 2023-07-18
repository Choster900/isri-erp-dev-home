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
        $rules = [
            'quedan.compromiso_ppto_det_doc_adquisicion'     => ['required_without:quedan.id_det_doc_adquisicion'],
            'quedan.numero_doc_adquisicion'     => ['required_without:quedan.id_det_doc_adquisicion'],

        ];
        collect($this->input('detalle_quedan', [])) // Convertir el arreglo en una colección
            ->each(function ($detalle, $key) use (&$rules) { // Iterar sobre cada detalle
                if (!empty($detalle["isDelete"])) { // Validar si el campo 7 está lleno
                    $rules["detalle_quedan.{$key}.id_dependencia"] = ['required'];
                    $rules["detalle_quedan.{$key}.numero_acta_det_quedan"] = [
                        Rule::unique('detalle_quedan', 'numero_acta_det_quedan')->where(function ($query) use ($detalle) {
                            return $query->where('id_dependencia', $detalle["id_dependencia"])->where('numero_acta_det_quedan', $detalle["numero_acta_det_quedan"]);
                        })->ignore($detalle["id_det_quedan"], 'id_det_quedan')
                    ];
                    $rules["detalle_quedan.{$key}.monto.producto_factura_det_quedan"] = ["required_without:detalle_quedan.{$key}.monto.servicio_factura_det_quedan"];
                    $rules["detalle_quedan.{$key}.monto.servicio_factura_det_quedan"] = ["required_without:detalle_quedan.{$key}.monto.producto_factura_det_quedan"];
                    if ($detalle["reajuste"]) {
                        $rules["detalle_quedan.{$key}.justificacion_det_quedan"] = ['required'];
                        $rules["detalle_quedan.{$key}.reajuste_monto.ajuste_producto_factura_det_quedan"] = ["required_with:detalle_quedan.{$key}.monto.producto_factura_det_quedan"];
                        $rules["detalle_quedan.{$key}.reajuste_monto.ajuste_servicio_factura_det_quedan"] = ["required_with:detalle_quedan.{$key}.monto.servicio_factura_det_quedan"];
                    }
                }
            });
        return $rules;
    }

    public function messages()
    {
        $messages = [];
        $messages = [
            'quedan.compromiso_ppto_det_doc_adquisicion.required_without'     => ['El numero de compromiso es requerido'],
            'quedan.numero_doc_adquisicion.required_without'     => ['El numero de acuerdo es requerido'],
        ];

        foreach ($this->input('detalle_quedan', []) as $key => $value) {
            if ($this->input("detalle_quedan.{$key}.isDelete")) {
                $messages["detalle_quedan.{$key}.id_dependencia.required"] = 'La dependencia es un dato requerido';
                $messages["detalle_quedan.{$key}.justificacion_det_quedan.required"] = 'La justificacion del reajuste es requerido';
                // Obtener el número de acta repetido
                $repeatedNumeroActa = $this->input("detalle_quedan.{$key}.numero_acta_det_quedan");
                // Obtener el nombre de la dependencia
                $dependenciaNombre = $this->input("detalle_quedan.{$key}.nombre_dependencia");
                // Construir el mensaje personalizado
                $customMessage = "El número de acta {$repeatedNumeroActa} ya ha sido utilizado en la dependencia {$dependenciaNombre}";
                // Reemplazar el número de acta en el mensaje de error
                $messages["detalle_quedan.{$key}.numero_acta_det_quedan.unique"] = $customMessage;
                $messages["detalle_quedan.{$key}.monto.producto_factura_det_quedan.required_without"] = 'Debe ingresar monto de producto o servicio';
                $messages["detalle_quedan.{$key}.monto.servicio_factura_det_quedan.required_without"] = 'Debe ingresar monto de producto o servicio';
                $messages["detalle_quedan.{$key}.reajuste_monto.ajuste_servicio_factura_det_quedan.required_with"] = 'El monto de reajuste por servicio es requerido';
                $messages["detalle_quedan.{$key}.reajuste_monto.ajuste_producto_factura_det_quedan.required_with"] = 'El monto de reajuste por producto es requerido';
            }
        }
        return $messages;
    }
}
