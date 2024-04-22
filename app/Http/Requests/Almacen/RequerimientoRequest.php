<?php

namespace App\Http\Requests\Almacen;

use App\Models\DetalleExistenciaAlmacen;
use App\Models\DetalleRequerimiento;
use Illuminate\Foundation\Http\FormRequest;

class RequerimientoRequest extends FormRequest
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
    public function rules()
    {
        $rules = [
            'idLt'             => 'required',
            'idCentroAtencion' => 'required',
            'idProyFinanciado' => 'required',
        ];

        collect($this->input('dataDetalleRequerimiento', []))
            ->each(function ($prodReq, $key) use (&$rules, &$messages) {
                if ($prodReq["stateCentroProduccion"] == 0) {
                    return;
                }
                $rules["dataDetalleRequerimiento.{$key}.idCentroProduccion"] = ['required'];


                collect($this->input("dataDetalleRequerimiento.{$key}.productos", []))
                    ->each(function ($det, $indice) use (&$rules, $key, $prodReq) {
                        if ($det["stateProducto"] == 0) {
                            return;
                        }

                        $rules["dataDetalleRequerimiento.{$key}.productos.{$indice}.cantDetRequerimiento"] = ['required'];
                        $rules["dataDetalleRequerimiento.{$key}.productos.{$indice}.idDetExistenciaAlmacen"] = ['required'];

                        if (!empty($det["idDetExistenciaAlmacen"] && empty($det["idDetRequerimiento"]))) {

                            $cantidadTotal = 0;

                            $detalleExistenciaAlmacen = DetalleExistenciaAlmacen::select("cant_det_existencia_almacen")
                                ->where('id_det_existencia_almacen', $det["idDetExistenciaAlmacen"])
                                ->first();

                            $cantidadDetalleExistencia = $detalleExistenciaAlmacen->cant_det_existencia_almacen;


                            $totalDetalleRequerimiento = DetalleRequerimiento::where('id_det_existencia_almacen', $det["idDetExistenciaAlmacen"])
                                ->sum('cant_det_requerimiento');

                            $cantidadTotal = $totalDetalleRequerimiento + $cantidadDetalleExistencia;


                            $rules["dataDetalleRequerimiento.{$key}.productos.{$indice}.cantDetRequerimiento"] = [
                                'required',
                                'numeric',
                                "lte:$cantidadTotal",
                            ];
                        }
                    });
            });

        return $rules;
    }


    public function messages()
    {
        $messages = [
            'idCentroAtencion.required' => 'El campo ID Centro de Atención es obligatorio.',
            'numRequerimiento.required' => 'El campo Número de Requerimiento es obligatorio.',
            'idProyFinanciado.required' => 'El campo ID Proyecto Financiado es obligatorio.',
            'idLt.required'             => 'El campo ID LT es obligatorio.',
        ];

        $messages = [
            'dataDetalleRequerimiento.*.productos.*.cantDetRequerimiento.required' => 'El campo Cantidad de Requerimiento es obligatorio.',
            'dataDetalleRequerimiento.*.productos.*.cantDetRequerimiento.numeric' => 'El campo Cantidad de Requerimiento debe ser numérico.',
            'dataDetalleRequerimiento.*.productos.*.cantDetRequerimiento.lte' => 'La cantidad de requerimiento debe ser menor o igual a :value.',
        ];


        collect($this->input('dataDetalleRequerimiento', []))
            ->each(function ($prodReq, $key) use (&$messages) {
                if ($prodReq["stateCentroProduccion"] == 0) {
                    return;
                }

                $messages["dataDetalleRequerimiento.{$key}.idCentroProduccion.required"] = 'El campo ID Centro de Producción es obligatorio.';

                collect($this->input("dataDetalleRequerimiento.{$key}.productos", []))
                    ->each(function ($det, $indice) use (&$messages, $key) {
                        if ($det["stateProducto"] == 0) {
                            return;
                        }

                        $messages["dataDetalleRequerimiento.{$key}.productos.{$indice}.idProducto.required"] = 'El campo ID Producto es obligatorio.';


                        $detalleExistenciaAlmacen = DetalleExistenciaAlmacen::find("dataDetalleRequerimiento.{$key}.productos.{$indice}.idDetExistenciaAlmacen");

                        $cantidadDisponible = $detalleExistenciaAlmacen;

                        $messages["dataDetalleRequerimiento.{$key}.productos.{$indice}.idDetExistenciaAlmacen.required"] = 'El campo ID Detalle de Existencia en Almacén es obligatorio.';

                        // Mensaje para la validación de cantidad recibida
                        $messages["dataDetalleRequerimiento.{$key}.productos.{$indice}.cantDetRequerimiento.required"] = 'El campo Cantidad de Requerimiento es obligatorio.';
                    });
            });

        return $messages;
    }


    /* {
        "idLt": null,
        "idCentroAtencion": null,
        "idProyFinanciado": null,
        "idEstadoReq": null,
        "numRequerimiento": null,
        "cantPersonalRequerimiento": null,
        "fechaRequerimiento": null,
        "observacionRequerimiento": null,
        "dataDetalleRequerimiento": [
            {
                "idDetRequerimiento": "",
                "idRequerimiento": "",
                "idCentroProduccion": "",
                "productos": [
                    {
                        "idDetRequerimiento": "",
                        "idProducto": "",
                        "descripcionProducto": "",
                        "cantDetRequerimiento": "",
                        "costoDetRequerimiento": "",
                        "stateProducto": 1
                    }
                ],
                "stateCentroProduccion": 1,
                "isHidden": false
            }
        ]
    } */
}
