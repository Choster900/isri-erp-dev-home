<?php

namespace App\Http\Controllers\Tesoreria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RequerimientoPago;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Requests\Tesoreria\RequerimientoRequest;

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
        $v_query = RequerimientoPago::select("*")
            ->orderBy($v_columns[$v_column], $v_dir);

        if ($data) {
            $v_query->where('id_requerimiento_pago', 'like', '%' . $data["id_requerimiento_pago"] . '%');
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
                'numero_requerimiento_pago'    => $request->input("numero_requerimiento_pago"),
                'mes_requerimiento_pago'       => $request->input("mes_requerimiento_pago"),
                'anio_requerimiento_pago'      => $request->input("anio_requerimiento_pago"),
                'fecha_requerimiento_pago'     => Carbon::now(),
                'fecha_reg_requerimiento_pago' => Carbon::now(),
                'usuario_requerimiento_pago '  => $request->user()->nick_usuario,
                'ip_requerimiento_pago'        => $request->ip(),
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
        try {
            DB::beginTransaction();
            $v_requerimiento = RequerimientoPago::where('id_requerimiento_pago', $request->input("id_requerimiento_pago"))->update([
                'numero_requerimiento_pago'    => $request->input("numero_requerimiento_pago"),
                'fecha_mod_requerimiento_pago' => Carbon::now(),
                'ip_requerimiento_pago'        => $request->ip(),
            ]);
            DB::commit();
            return $v_requerimiento;
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json($th->getMessage(), 500);
        }
    }
}