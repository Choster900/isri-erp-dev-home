<?php

namespace App\Http\Controllers\tesoreria;

use App\Http\Controllers\Controller;
use App\Models\Quedan;
use Illuminate\Http\Request;
use App\Models\LiquidacionQuedan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class LiquidacionQuedanController extends Controller
{

    public function processLiquidacionQuedan(Request $request)
    {
        try {
            $quedan = $request->params;

            foreach ( $quedan as $key => $value ) { //creando o actualizando la liquidacion del quedan
                LiquidacionQuedan::updateOrCreate(
                    ['id_quedan' => $value["id_quedan"]],
                    [
                        'monto_liquidacion_quedan'    => $value["monto_liquidacion_quedan"],
                        'notifica_liquidacion_quedan' => $value["notifica_liquidacion_quedan"],
                        'fecha_liquidacion_quedan'    => Carbon::now(),
                    ]
                );
            }

            foreach ( $quedan as $key => $value ) {

                $monto_liquido_quedan = Quedan::select('*')->where('id_quedan', $value["id_quedan"])->get();
                if ($monto_liquido_quedan[0]->monto_liquido_quedan === $value["monto_liquidacion_quedan"]) {
                    Quedan::where("id_quedan", $value["id_quedan"])->update([
                        'id_estado_quedan' => 4,
                    ]);
                } else {
                    Quedan::where("id_quedan", $value["id_quedan"])->update([
                        'id_estado_quedan' => 3,
                    ]);
                }

            }

            return "Success";
        } catch (\Throwable $th) {
            // manejo de excepciones
        }
    }

}