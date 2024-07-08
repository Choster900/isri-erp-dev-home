<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Models\SubAlmacen;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubAlmacenController extends Controller
{
    public function getSubAlmacenes(Request $request)
    {
        $v_columns = [
            'id_sub_almacen',
            'nombre_sub_almacen',
            'responsable_sub_almacen',
        ];

        $v_length = $request->input('length');
        $v_column = $request->input('column');
        $v_dir = $request->input('dir');
        $data = $request->input('search');

        $v_query = SubAlmacen::with(["empleado.persona"])->where("estado_sub_almacen", 1)
            ->orderBy($v_columns[$v_column + 1], $v_dir);
        if ($data) {
            $v_query->where('id_sub_almacen', 'like', '%' . $data["id_sub_almacen"] . '%')
                ->where('nombre_sub_almacen', 'like', '%' . $data["nombre_sub_almacen"] . '%');


            if (isset($data['responsable_sub_almacen'])) {
                $v_query->whereHas('empleado.persona', function ($query) use ($data) {
                    $query->whereRaw(
                        "MATCH (pnombre_persona, snombre_persona, tnombre_persona, papellido_persona, sapellido_persona, tapellido_persona) AGAINST (?)",
                        [$data['responsable_sub_almacen']]
                    );
                });
            }
        }

        $v_requerimientos = $v_query->paginate($v_length)->onEachSide(1);
        return [
            'data' => $v_requerimientos,
            'draw' => $request->input('draw'),
        ];
    }

    function saveSubAlmacen(Request $request)
    {

        try {
            DB::beginTransaction();
            $v_requerimiento = SubAlmacen::create([
                'nombre_sub_almacen'    => $request->nombreSubAlmacen,
                'id_empleado'           => $request->idEmpleado,
                'estado_sub_almacen'    => 1,
                'fecha_reg_sub_almacen' => Carbon::now(),
                'usuario_sub_almacen'   => $request->user()->nick_usuario,
                'ip_sub_almacen'        => $request->ip(),
            ]);
            DB::commit();
            return $v_requerimiento;
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json($th->getMessage(), 500);
        }
    }

    function updateSubAlmacen(Request $request)
    {

        try {
            DB::beginTransaction();
            $v_requerimiento = SubAlmacen::where("id_sub_almacen", $request->idSubAlmacen)->update([
                'nombre_sub_almacen'    => $request->nombreSubAlmacen,
                'id_empleado'           => $request->idEmpleado,
                'estado_sub_almacen'    => 1,
                'fecha_mod_sub_almacen' => Carbon::now(),
                'usuario_sub_almacen'   => $request->user()->nick_usuario,
                'ip_sub_almacen'        => $request->ip(),
            ]);
            DB::commit();
            return $v_requerimiento;
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json($th->getMessage(), 500);
        }
    }

    function deleteSubAlmacen(Request $request)
    {

        try {
            DB::beginTransaction();
            $v_requerimiento = SubAlmacen::where("id_sub_almacen", $request->idSubAlmacen)->update([
                'estado_sub_almacen' => 0,
            ]);
            DB::commit();
            return $v_requerimiento;
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json($th->getMessage(), 500);
        }
    }

    function findEmployeeByName(Request $request)
    {
        $empleado = Empleado::whereHas('persona', function ($query) use ($request) {
            $query->whereRaw(
                "MATCH (pnombre_persona, snombre_persona, tnombre_persona, papellido_persona, sapellido_persona, tapellido_persona) AGAINST (?)",
                [$request->nombre]
            );
        })->with('persona')->get();

        $formattedResults = $empleado->map(function ($item) {
            return [
                'value'           => $item->persona->id_persona,
                'label'           => $item->persona->nombre_completo,
                'allDataPersonas' => $item->persona,
            ];
        });

        return response()->json($formattedResults);
    }
}
