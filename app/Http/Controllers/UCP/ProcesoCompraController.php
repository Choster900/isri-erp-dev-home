<?php

namespace App\Http\Controllers\UCP;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Models\PlazaAsignada;
use App\Models\ProcesoCompra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcesoCompraController extends Controller
{
    //

    public function getProcesosCompras(Request $request)
    {
        $v_columns = [
            'id_proceso_compra',
            'id_tipo_proceso_compra',
            'nombre_proceso_compra',
        ];

        $v_length = $request->input('length');
        $v_column = $request->input('column');
        $v_dir = $request->input('dir');
        $data = $request->input('search');

        $v_query = ProcesoCompra::with(["empleado.persona", "tipo_proceso_compra"])
            ->orderBy($v_columns[$v_column], $v_dir); // Ajustar $v_columns[$v_column] si column es el índice correcto
        if ($data) {
            $v_query->where('id_proceso_compra', 'like', '%' . $data["id_proceso_compra"] . '%');
            /*  ->where('id_tipo_proceso_compra', 'like', '%' . $data["id_tipo_proceso_compra"] . '%')
                ->where('nombre_proceso_compra', 'like', '%' . $data["nombre_proceso_compra"] . '%');


            if (isset($data['responsable_sub_almacen'])) {
                $v_query->whereHas('empleado.persona', function ($query) use ($data) {
                    $query->whereRaw(
                        "MATCH (pnombre_persona, snombre_persona, tnombre_persona, papellido_persona, sapellido_persona, tapellido_persona) AGAINST (?)",
                        [$data['responsable_sub_almacen']]
                    );
                });
            } */
        }

        $v_requerimientos = $v_query->paginate($v_length)->onEachSide(1);
        return [
            'data' => $v_requerimientos,
            'draw' => $request->input('draw'),
        ];
    }


    public function getProcesosComprasForAlmacen(Request $request)
    {
        $v_columns = [
            'id_proceso_compra',
            'id_tipo_proceso_compra',
            'nombre_proceso_compra',
        ];

        $v_length = $request->input('length');
        $v_column = $request->input('column');
        $v_dir = $request->input('dir');
        $data = $request->input('search');

        $v_query = ProcesoCompra::with(["empleado.persona", "tipo_proceso_compra"])->where("id_tipo_proceso_compra", 1)
            ->orderBy($v_columns[$v_column], $v_dir); // Ajustar $v_columns[$v_column] si column es el índice correcto
        if ($data) {
            $v_query->where('id_proceso_compra', 'like', '%' . $data["id_proceso_compra"] . '%');
            /*  ->where('id_tipo_proceso_compra', 'like', '%' . $data["id_tipo_proceso_compra"] . '%')
                ->where('nombre_proceso_compra', 'like', '%' . $data["nombre_proceso_compra"] . '%');


            if (isset($data['responsable_sub_almacen'])) {
                $v_query->whereHas('empleado.persona', function ($query) use ($data) {
                    $query->whereRaw(
                        "MATCH (pnombre_persona, snombre_persona, tnombre_persona, papellido_persona, sapellido_persona, tapellido_persona) AGAINST (?)",
                        [$data['responsable_sub_almacen']]
                    );
                });
            } */
        }

        $v_requerimientos = $v_query->paginate($v_length)->onEachSide(1);
        return [
            'data' => $v_requerimientos,
            'draw' => $request->input('draw'),
        ];
    }



    function saveProcesoCompra(Request $request)
    {

        try {
            DB::beginTransaction();
            $v_requerimiento = ProcesoCompra::create([
                'id_tipo_proceso_compra' => $request->tipoProcesoCompra,
                'id_empleado'            => null, // enviamos null por que es en UCP solo se va a poder agregar desde almacen
                'nombre_proceso_compra'  => $request->nombreProcesoCompra,
                /*   'fecha_reg_sub_almacen' => Carbon::now(),
                'usuario_sub_almacen'   => $request->user()->nick_usuario,
                'ip_sub_almacen'        => $request->ip(), */
            ]);
            DB::commit();
            return $v_requerimiento;
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json($th->getMessage(), 500);
        }
    }

    function updateProcesoCompra(Request $request)
    {

        try {
            DB::beginTransaction();
            $v_requerimiento = ProcesoCompra::where("id_proceso_compra", $request->idProcesoCompra)->update([
                'id_tipo_proceso_compra' => $request->tipoProcesoCompra,
                'nombre_proceso_compra'  => $request->nombreProcesoCompra,
                /* 'fecha_mod_sub_almacen' => Carbon::now(),
                'usuario_sub_almacen'   => $request->user()->nick_usuario,
                'ip_sub_almacen'        => $request->ip(), */
            ]);
            DB::commit();
            return $v_requerimiento;
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json($th->getMessage(), 500);
        }
    }

    function updateProcesoCompraEmployee(Request $request)
    {

        try {
            DB::beginTransaction();
            $v_requerimiento = ProcesoCompra::where("id_proceso_compra", $request->idProcesoCompra)->update([
                'id_empleado' => $request->idEmpleado,
                /* 'fecha_mod_sub_almacen' => Carbon::now(),
                'usuario_sub_almacen'   => $request->user()->nick_usuario,
                'ip_sub_almacen'        => $request->ip(), */
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
            $v_requerimiento = ProcesoCompra::where("id_sub_almacen", $request->idSubAlmacen)->update([
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

    // Get employees by almacen
    function getEmployeeByDependencia()
    {
        $employees = PlazaAsignada::with(["empleado.persona"])->where("id_dependencia", 21)->get();



        $formattedResults = $employees->map(function ($item) {
            return [
                'value'           => $item->empleado->persona->id_persona,
                'label'           => $item->empleado->persona->nombre_completo,
                'allDataPersonas' => $item->persona,
            ];
        });

        return response()->json($formattedResults);

    }
}
