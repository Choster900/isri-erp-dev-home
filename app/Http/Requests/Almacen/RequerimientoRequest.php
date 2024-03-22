<?php

namespace App\Http\Requests\Almacen;

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
        $rules = [];

        collect($this->input('dataDetalleRequerimiento', []))
            ->each(function ($productoAdq, $key) use (&$rules) {
                $rules["dataDetalleRequerimiento.{$key}.idCentroProduccion"] = ['required'];

                collect($this->input("dataDetalleRequerimiento.{$key}.productos", []))
                    ->each(function ($det, $indice) use (&$rules, $key) {
                        if ($det["stateProducto"] == 0) {
                            return;
                        }
                        $rules["productAdq.{$key}.detalleDoc.{$indice}.idMarca"] = ['required'];
                    });
            });

        return $rules;
    }
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
