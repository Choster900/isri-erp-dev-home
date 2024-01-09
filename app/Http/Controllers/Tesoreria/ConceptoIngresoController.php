<?php

namespace App\Http\Controllers\Tesoreria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ConceptoIngreso;
use App\Models\CuentaPresupuestal;
use App\Models\Dependencia;
use App\Models\ProyectoFinanciado;
use Carbon\Carbon;
use App\Http\Requests\Tesoreria\IncomeConceptRequest;
use App\Models\CentroAtencion;
use Illuminate\Support\Facades\DB;

class ConceptoIngresoController extends Controller
{
    public function getConceptoIngresos(Request $request)
    {
        $columns = ['id_concepto_ingreso', 'nombre_dependencia', 'nombre_concepto_ingreso', 'id_ccta_presupuestal', 'estado_concepto_ingreso'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = ConceptoIngreso::select('*')
            ->leftJoin('centro_atencion', function ($join) {
                $join->on('concepto_ingreso.id_centro_atencion', '=', 'centro_atencion.id_centro_atencion');
            })
            ->orderBy($columns[$column], $dir);
        if ($search_value) {
            $query->where([
                ['id_concepto_ingreso', 'like', '%' . $search_value['id_concepto_ingreso'] . '%'],
                ['nombre_concepto_ingreso', 'like', '%' . $search_value['nombre_concepto_ingreso'] . '%'],
                ['id_ccta_presupuestal', 'like', '%' . $search_value['id_ccta_presupuestal'] . '%'],
                ['estado_concepto_ingreso', 'like', '%' . $search_value['estado_concepto_ingreso'] . '%'],
                [function ($query) use ($search_value) {
                    if ($search_value['nombre_dependencia'] == 'N/A' || $search_value['nombre_dependencia'] == 'n/a') {
                        $query->where('concepto_ingreso.id_centro_atencion', null);
                    } else {
                        $query->whereRaw('IFNULL(nombre_centro_atencion, "") like ?', '%' . $search_value['nombre_dependencia'] . '%')
                            ->orWhereRaw('IFNULL(codigo_centro_atencion, "") like ?', '%' . $search_value['nombre_dependencia'] . '%');
                    }
                }],
            ]);
        }
        $income_concept = $query->paginate($length)->onEachSide(1);
        return ['data' => $income_concept, 'draw' => $request->input('draw')];
    }

    public function changeStateIncomeConcept(Request $request)
    {
        $servicio = ConceptoIngreso::find($request->id);
        if ($servicio->estado_concepto_ingreso == 1) {
            if ($request->status == 1) {
                $servicio->update([
                    'estado_concepto_ingreso' => 0,
                    'fecha_mod_concepto_ingreso' => Carbon::now(),
                    'usuario_concepto_ingreso' => $request->user()->nick_usuario,
                    'ip_concepto_ingreso' => $request->ip(),
                ]);
                return ['message' => 'Concepto de ingreso ' . $servicio->nombre_concepto_ingreso . ' ha sido desactivado con exito'];
            } else {
                return ['message' => 'El concepto de ingreso seleccionado ya ha sido activado por otro usuario'];
            }
        } else {
            if ($servicio->estado_concepto_ingreso == 0) {
                if ($request->status == 0) {
                    $servicio->update([
                        'estado_concepto_ingreso' => 1,
                        'fecha_mod_concepto_ingreso' => Carbon::now(),
                        'usuario_concepto_ingreso' => $request->user()->nick_usuario,
                        'ip_concepto_ingreso' => $request->ip(),
                    ]);
                    return ['message' => 'Concepto de ingreso ' . $servicio->nombre_concepto_ingreso . ' ha sido activado con exito'];
                } else {
                    return ['message' => 'El concepto de ingreso seleccionado ya ha sido desactivado por otro usuario'];
                }
            }
        }
    }

    //New method for composition API
    public function getInfoModalConceptoIngreso(Request $request, $id)
    {
        $concept = ConceptoIngreso::with('centro_atencion')->find($id);
        $budget_accounts = CuentaPresupuestal::selectRaw("id_ccta_presupuestal as value , concat(id_ccta_presupuestal, ' - ', nombre_ccta_presupuestal) as label")
            ->where('tesoreria_ccta_presupuestal', '=', 1)
            ->where('estado_ccta_presupuestal', '=', 1)
            ->orderBy('nombre_ccta_presupuestal')
            ->get();
        $dependencies = CentroAtencion::selectRaw("id_centro_atencion as value , concat(codigo_centro_atencion, ' - ', nombre_centro_atencion) as label")
            ->orderBy('nombre_centro_atencion')
            ->get();
        $financing_sources = ProyectoFinanciado::select('id_proy_financiado as value', 'nombre_proy_financiado as label')
            ->where('estado_proy_financiado', '=', 1)
            ->orderBy('nombre_proy_financiado')
            ->get();

        return response()->json([
            'budget_accounts'           => $budget_accounts,
            'dependencies'              => $dependencies,
            'financing_sources'         => $financing_sources,
            'concept'                   => $concept ?? []
        ]);
    }


    public function getSelectsIncomeConcept(Request $request)
    {
        $budget_accounts = CuentaPresupuestal::selectRaw("id_ccta_presupuestal as value , concat(id_ccta_presupuestal, ' - ', nombre_ccta_presupuestal) as label")
            ->where('tesoreria_ccta_presupuestal', '=', 1)
            ->where('estado_ccta_presupuestal', '=', 1)
            ->orderBy('nombre_ccta_presupuestal')
            ->get();
        //This was before.
        // $dependencies = Dependencia::selectRaw("id_dependencia as value , concat(codigo_dependencia, ' - ', nombre_dependencia) as label")
        //     ->where('id_tipo_dependencia', '=', 1)
        //     ->orderBy('nombre_dependencia')
        //     ->get();
        $dependencies = CentroAtencion::selectRaw("id_centro_atencion as value , concat(codigo_centro_atencion, ' - ', nombre_centro_atencion) as label")
            ->orderBy('nombre_centro_atencion')
            ->get();
        $financing_sources = ProyectoFinanciado::select('id_proy_financiado as value', 'nombre_proy_financiado as label')
            ->where('estado_proy_financiado', '=', 1)
            ->orderBy('nombre_proy_financiado')
            ->get();

        return ['budget_accounts' => $budget_accounts, 'dependencies' => $dependencies, 'financing_sources' => $financing_sources];
    }

    public function saveIncomeConcept(IncomeConceptRequest $request)
    {
        DB::beginTransaction();
        try {
            $dependency = new ConceptoIngreso([
                'id_centro_atencion'                        => $request->dependency_id,
                'id_ccta_presupuestal'                      => $request->budget_account_id,
                'id_proy_financiado'                        => $request->financing_source_id,
                'nombre_concepto_ingreso'                   => $request->name,
                'detalle_concepto_ingreso'                  => $request->detail,
                'estado_concepto_ingreso'                   => 1,
                'fecha_reg_concepto_ingreso'                => Carbon::now(),
                'usuario_concepto_ingreso'                  => $request->user()->nick_usuario,
                'ip_concepto_ingreso'                       => $request->ip(),
            ]);
            $dependency->save();

            DB::commit(); // Confirma las operaciones en la base de datos
            return response()->json([
                'message'          => 'Concepto de ingreso guardado con éxito.',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack(); // En caso de error, revierte las operaciones anteriores
            return response()->json([
                'logical_error' => 'Ha ocurrido un error con sus datos.',
                'error' => $th->getMessage(),
            ], 422);
        }
    }
    public function updateIncomeConcept(IncomeConceptRequest $request)
    {
        $income_concept = ConceptoIngreso::find($request->income_concept_id);
        if ($income_concept->estado_concepto_ingreso == 0) {
            return response()->json(['logical_error' => 'Error, el concepto de ingreso seleccionado ha sido desactivado por otro usuario.'], 422);
        } else {
            DB::beginTransaction();
            try {
                $income_concept->update([
                    'id_centro_atencion'                        => $request->dependency_id,
                    'id_ccta_presupuestal'                      => $request->budget_account_id,
                    'id_proy_financiado'                        => $request->financing_source_id,
                    'nombre_concepto_ingreso'                   => $request->name,
                    'detalle_concepto_ingreso'                  => $request->detail,
                    'estado_concepto_ingreso'                   => 1,
                    'fecha_mod_concepto_ingreso'                => Carbon::now(),
                    'usuario_concepto_ingreso'                  => $request->user()->nick_usuario,
                    'ip_concepto_ingreso'                       => $request->ip(),
                ]);

                DB::commit(); // Confirma las operaciones en la base de datos
                return response()->json([
                    'message'          => 'Concepto de ingreso actualizado con éxito.',
                ]);
            } catch (\Throwable $th) {
                DB::rollBack(); // En caso de error, revierte las operaciones anteriores
                return response()->json([
                    'logical_error' => 'Ha ocurrido un error con sus datos.',
                    'error' => $th->getMessage(),
                ], 422);
            }
        }
    }
}
