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

    public function getRequerimientosAsiganos(Request $request)
    {
        $v_columns = [
            'id_requerimiento_pago',
            'numero_requerimiento_pago',
            'fecha_requerimiento_pago',
            'fecha_reg_requerimiento_pago',
            'fecha_mod_requerimiento_pago',
            'id_estado_req_pago',
            'usuario_requerimiento_pago',
            'ip_requerimiento_pago',
            'descripcion_requerimiento_pago',
        ];

        $v_length = $request->input('length');
        $v_column = $request->input('column');
        $v_dir = $request->input('dir');
        $data = $request->input('search');
        $v_query = RequerimientoPago::select("*")->with(["Quedan", "Quedan.liquidacion_quedan", "Quedan.proveedor"])
            ->orderBy($v_columns[$v_column], $v_dir)->whereHas("Quedan");
        if ($data) {
            $v_query->where('numero_requerimiento_pago', 'like', '%' . $data["numero_requerimiento_pago"] . '%');


            if (isset($data['descripcion_requerimiento_pago'])) {
                $v_query->where(function ($query) use ($data) {
                    $query->where('descripcion_requerimiento_pago', 'like', '%' . $data['descripcion_requerimiento_pago'] . '%')
                        ->orWhereNull('descripcion_requerimiento_pago');
                });
            }
            if (isset($data['id_estado_req_pago'])) {
                $v_query->where('id_estado_req_pago',  $data["id_estado_req_pago"]);
            }

            $v_query->where('monto_requerimiento_pago', 'like', '%' . $data["monto_requerimiento_pago"] . '%');



            if (isset($data['allQUedan'])) {
                $searchText = $data["allQUedan"];
                $v_query->whereHas('Quedan', function ($query) use ($searchText) {
                    $query->where(function ($query) use ($searchText) {
                        $query->where('id_quedan', 'like', '%' . $searchText . '%')
                            ->orWhere('monto_liquido_quedan', 'like', '%' . $searchText . '%');
                    });
                });
            }
        }

        $v_requerimientos = $v_query->paginate($v_length)->onEachSide(1);
        return [
            'data' => $v_requerimientos,
            'draw' => $request->input('draw'),
        ];
    }


    public function processLiquidacionQuedan(Request $request)
    {

        try {
            $quedan = $request->params;
            $totalQuedan = [];
            foreach ($quedan as $key => $value) {
                if ($value["monto_liquidacion_quedan"] > 0 && $value["eliminadoLogicoQuedan"] == true) {
                    LiquidacionQuedan::create(
                        [
                            'id_quedan'                   => $value["id_quedan"],
                            'monto_liquidacion_quedan'    => $value["monto_liquidacion_quedan"],
                            'notifica_liquidacion_quedan' => $value["notifica_liquidacion_quedan"],
                            'fecha_liquidacion_quedan'    => Carbon::now(),
                        ]
                    );
                    $montoLiquido = LiquidacionQuedan::where('id_quedan', $value["id_quedan"])->sum('monto_liquidacion_quedan');
                    $totalQuedan[$key] = [
                        "id_quedan" => $value["id_quedan"],
                        "total"     => $montoLiquido,
                    ];

                    $monto_liquido_quedan = Quedan::select('*')->where('id_quedan', $value["id_quedan"])->get();


                    if ($monto_liquido_quedan[0]->monto_liquido_quedan === $montoLiquido) {
                        Quedan::where("id_quedan", $value["id_quedan"])->update([
                            'id_estado_quedan' => 4,
                        ]);
                    } else if ($value["monto_liquidacion_quedan"] != 0) {
                        Quedan::where("id_quedan", $value["id_quedan"])->update([
                            'id_estado_quedan' => 3,
                        ]);
                    }
                }
            }

            if ($request->liquidado) {
                RequerimientoPago::where("id_requerimiento_pago", $request->id_requerimiento_pago)->update(["id_estado_req_pago" => 2]);
            }

            return $totalQuedan;

            //return $request;
        } catch (\Throwable $th) {
            // manejo de excepciones
        }
    }
    public function totalLiquidacionesByQuedan()
    {
        //return LiquidacionQuedan::select("*")->with(["quedan"])->get();

        return RequerimientoPago::with(["quedan", "quedan.liquidacion_quedan"])->get();
    }
    public function deleteQuedanFromRequest(Request $request)
    {
        Quedan::where("id_quedan", $request->id_quedan)->update(['id_requerimiento_pago' => null, "id_estado_quedan" => 1]);

        return response()->json(['message' => 'Actualización exitosa'], 200);
    }
}
