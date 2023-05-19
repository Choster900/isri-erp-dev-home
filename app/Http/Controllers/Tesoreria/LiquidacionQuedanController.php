<?php

namespace App\Http\Controllers\tesoreria;

use App\Http\Controllers\Controller;
use App\Models\Quedan;
use App\Models\RequerimientoPago;
use Illuminate\Http\Request;
use App\Models\LiquidacionQuedan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class LiquidacionQuedanController extends Controller
{

    public function processLiquidacionQuedan(Request $request)
    {   
        //TODO: VER UNA FORMA DE TRAER LA SUMA DE TODOS LOS QUEDAN DE TODOS LOS REQUERIMIENTOS SIN HACER ALGUNA CONSULTA ANTES
        //POR QUE NO QUIERO QUE TARDE TANTO AL ABRIR EL MODALN Y PUES ESO
        try {
            $quedan = $request->params;

            /* if ($request->liquidado) {
            RequerimientoPago::where("id_requerimiento_pago", $request->requerimiento)->update(["id_estado_req_pago" => 2]);
            } */
            //variable que acumulara el total monto por id_quedan
            $totalQuedan = [];
            foreach ( $quedan as $key => $value ) { //creando o actualizando la liquidacion del quedan
                if ($value["monto_liquidacion_quedan"] > 0) {
                    LiquidacionQuedan::create(
                        [
                            'id_quedan'                   => $value["id_quedan"],
                            'monto_liquidacion_quedan'    => $value["monto_liquidacion_quedan"],
                            'notifica_liquidacion_quedan' => $value["notifica_liquidacion_quedan"],
                            'fecha_liquidacion_quedan'    => Carbon::now(),
                        ]
                    );
                    $totalQuedan[$key] = [
                        "id_quedan" => $value["id_quedan"],
                        "total"     => LiquidacionQuedan::where('id_quedan', $value["id_quedan"])->sum('monto_liquidacion_quedan'),
                    ];
                }
            }
            return $totalQuedan;
            /* foreach ( $quedan as $key => $value ) {
            $monto_liquido_quedan = Quedan::select('*')->where('id_quedan', $value["id_quedan"])->get();
            if ($monto_liquido_quedan[0]->monto_liquido_quedan === $value["monto_liquidacion_quedan"]) {
            Quedan::where("id_quedan", $value["id_quedan"])->update([
            'id_estado_quedan' => 4,
            ]);
            } else if ($value["monto_liquidacion_quedan"] != 0) {
            Quedan::where("id_quedan", $value["id_quedan"])->update([
            'id_estado_quedan' => 3,
            ]);
            }
            } */

            //return $request;
        } catch (\Throwable $th) {
            // manejo de excepciones
        }
    }

}