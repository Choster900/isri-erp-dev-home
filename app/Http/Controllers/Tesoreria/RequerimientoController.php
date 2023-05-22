<?php

namespace App\Http\Controllers\Tesoreria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RequerimientoPago;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Requests\Tesoreria\RequerimientoRequest;
use App\Models\LiquidacionQuedan;
use App\Models\Quedan;

class RequerimientoController extends Controller
{
    public function getRequerimientos(Request $request)
    {
        $v_columns = [
            'id_requerimiento_pago',
            'numero_requerimiento_pago',
            'fecha_requerimiento_pago',
            'fecha_reg_requerimiento_pago',
            'fecha_mod_requerimiento_pago',
            'usuario_requerimiento_pago',
            'ip_requerimiento_pago',
        ];

        $v_length = $request->input('length');
        $v_column = $request->input('column'); //Index
        $v_dir = $request->input('dir');
        $data = $request->input('search');
        $v_query = RequerimientoPago::select("*")->with(["Quedan", "Quedan.liquidacion_quedan", "Quedan.proveedor"])
            ->orderBy($v_columns[$v_column], $v_dir);

        if ($data) {
            $v_query->where('id_requerimiento_pago', 'like', '%' . $data["id_requerimiento_pago"] . '%')
                ->where('numero_requerimiento_pago', 'like', '%' . $data["numero_requerimiento_pago"] . '%');

            if (isset($data['descripcion_requerimiento_pago'])) {
                $v_query->where('descripcion_requerimiento_pago', 'like', '%' . $data["descripcion_requerimiento_pago"] . '%');
            }

            $v_query->where('monto_requerimiento_pago', 'like', '%' . $data["monto_requerimiento_pago"] . '%');

            $searchText = $data["allQUedan"];
            $v_query->whereHas('Quedan', function ($query) use ($searchText) {
                $query->where(function ($query) use ($searchText) {
                    $query->where('id_quedan', 'like', '%' . $searchText . '%')
                        ->orWhere('monto_liquido_quedan', 'like', '%' . $searchText . '%');
                });
            });
        }

        $v_requerimientos = $v_query->paginate($v_length)->onEachSide(1);
        return [
            'data' => $v_requerimientos,
            'draw' => $request->input('draw'),
        ];
    }
    public function addRequerimientoNumber(RequerimientoRequest $request)
    {
        try {
            DB::beginTransaction();
            $v_requerimiento = RequerimientoPago::create([
                'numero_requerimiento_pago'      => $request->input("numero_requerimiento_pago"),
                'mes_requerimiento_pago'         => $request->input("mes_requerimiento_pago"),
                'anio_requerimiento_pago'        => $request->input("anio_requerimiento_pago"),
                'descripcion_requerimiento_pago' => $request->input("descripcion_requerimiento_pago"),
                'monto_requerimiento_pago'       => $request->input("monto_requerimiento_pago"),
                'fecha_requerimiento_pago'       => Carbon::now(),
                'estado_requerimiento_pago'      => 1,
                'id_estado_req_pago'             => 1,
                'fecha_reg_requerimiento_pago'   => Carbon::now(),
                'usuario_requerimiento_pago'     => $request->user()->nick_usuario,
                'ip_requerimiento_pago'          => $request->ip(),
            ]);
            DB::commit();
            return $v_requerimiento;
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json($th->getMessage(), 500);
        }
    }
    public function updateRequerimientoNumber(RequerimientoRequest $request)
    {
        $requirement = RequerimientoPago::find($request->id_requerimiento_pago);
        $assigned_amount_requirement = $requirement->Quedan()->sum('monto_liquido_quedan');
        if ($assigned_amount_requirement > $request->monto_requerimiento_pago) {
            return response()->json(['logical_error' => 'Error, no puede reducir el monto del requerimiento a un valor menor a lo ya asignado: $' . $assigned_amount_requirement], 422);
        } else {
            try {
                DB::beginTransaction();
                $v_requerimiento = RequerimientoPago::where('id_requerimiento_pago', $request->input("id_requerimiento_pago"))->update([
                    'numero_requerimiento_pago'      => $request->input("numero_requerimiento_pago"),
                    'descripcion_requerimiento_pago' => $request->input("descripcion_requerimiento_pago"),
                    'monto_requerimiento_pago'       => $request->input("monto_requerimiento_pago"),
                    'fecha_mod_requerimiento_pago'   => Carbon::now(),
                    'usuario_requerimiento_pago'    => $request->user()->nick_usuario,
                    'ip_requerimiento_pago'          => $request->ip(),
                ]);
                DB::commit();
                return $v_requerimiento;
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json($th->getMessage(), 500);
            }
        }
    }
    public function filterQuedan(Request $request) //TODO:POR TERMINAR 
    {
        $query = Quedan::select('*')
            ->with(["detalle_quedan", "proveedor.giro", "proveedor.sujeto_retencion", "tesorero"])
            ->orderBy("id_quedan", "desc")
            ->where("id_estado_quedan", 1)
            ->when($request["data"]["suppiler"], function ($query) use ($request) {
                return $query->where("id_proveedor", $request["data"]["suppiler"]);
            })
            ->when($request["data"]["rangeDate"], function ($query) use ($request) {
                $date = explode("to", $request["data"]["rangeDate"]);
                return $query->whereDate('fecha_emision_quedan', '>=', $date[0])
                    ->whereDate('fecha_emision_quedan', '<=', $date[1]);
            })
            ->when($request["data"]["iva"], function ($query) use ($request) {
                return $query->where("monto_iva_quedan", "like", "%" . $request["data"]["iva"] . "%");
            })
            ->when($request["data"]["isr"], function ($query) use ($request) {
                return $query->where("monto_isr_quedan", "like", "%" . $request["data"]["isr"] . "%");
            })
            ->when($request["data"]["monto"], function ($query) use ($request) {
                return $query->where("monto_liquido_quedan", "like", "%" . $request["data"]["monto"] . "%");
            })
            ->get();

        return $query;
    }

    public function addANumerRequestToQuedan(Request $request)
    {
        // Definir las reglas de validación
        $rules = [
            'itemsToAddNumber' => 'required|array',
            'numberRequest'    => 'required'
        ];
        // Validar los campos del request
        $request->validate($rules);
        //sumando el total del requerimiento_pago
        $totRequerimiento = 0;
        foreach ($request->itemsToAddNumber as $key => $value) {
            $totRequerimiento = $totRequerimiento + $value["monto_liquido_quedan"];
        }
        $monto_requerimiento_pago = RequerimientoPago::select('*')->where('id_requerimiento_pago', $request->numberRequest)->get();
        $quedanExistente = Quedan::select('*')->where('id_requerimiento_pago', $request->numberRequest)->get();
        foreach ($quedanExistente as $key => $value) {
            $totRequerimiento = $totRequerimiento + $value["monto_liquido_quedan"];
        }
        if ($totRequerimiento <= $monto_requerimiento_pago[0]->monto_requerimiento_pago) {
            foreach ($request->itemsToAddNumber as $key => $value) {
                $tieneHistorial = LiquidacionQuedan::where("id_quedan", $value["id_quedan"])->get();
                $estado = $tieneHistorial->isEmpty() ? 2 : 3;
                Quedan::where("id_quedan", $value["id_quedan"])->update([
                    'id_requerimiento_pago'     => $request->numberRequest, 
                    "id_estado_quedan"          => $estado,
                    'usuario_quedan'            => $request->user()->nick_usuario,
                    'fecha_mod_quedan'          => Carbon::now(),
                    'ip_quedan'                 => $request->ip(),
                ]);
            }
            return response()->json(['message' => 'Actualización exitosa'], 200);
        } else {
            DB::rollback();
            return response()
                ->json('Error, el monto total a asignar es de: $'.$totRequerimiento.' el cual excede el techo de $'.$monto_requerimiento_pago[0]->monto_requerimiento_pago.' del requerimiento.', 422);
        }
    }
    //El monto total a asignar es de: $XXXX el cual excede el techo asignado al requerimiento que es de: $XXXX

    public function takeOfNumberRequest(Request $request)
    {
        # code...
        Quedan::where("id_quedan", $request->id_quedan)->update([
            'id_requerimiento_pago'     => null, 
            "id_estado_quedan"          => 1,
            'usuario_quedan'            => $request->user()->nick_usuario,
            'fecha_mod_quedan'          => Carbon::now(),
            'ip_quedan'                 => $request->ip(),
        ]);
        return response()->json(['message' => 'Actualización exitosa'], 200);
    }
}