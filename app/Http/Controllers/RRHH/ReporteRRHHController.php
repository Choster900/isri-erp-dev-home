<?php

namespace App\Http\Controllers\RRHH;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Dependencia;
use App\Models\Empleado;
use Illuminate\Support\Facades\DB;

class ReporteRRHHController extends Controller
{
    public function getInfoForReports(Request $request)
    {
        $dependencies = DB::table('dependencia')
            ->selectRaw("
                dependencia.id_dependencia as value,
                CASE
                    WHEN dependencia.dep_id_dependencia IS NOT NULL THEN CONCAT(dep.codigo_dependencia, ' - ', dependencia.nombre_dependencia,' (',dependencia.codigo_dependencia,')')
                    ELSE CONCAT(dependencia.codigo_dependencia, ' - ', dependencia.nombre_dependencia)
                END as label,
                dependencia.id_dependencia,
                dependencia.dep_id_dependencia as depPadre
            ")
            ->leftJoin('dependencia as dep', 'dependencia.dep_id_dependencia', '=', 'dep.id_dependencia')
            ->where('dependencia.estado_dependencia', 1) //Dependencias activas
            ->where('dependencia.id_dependencia', '!=', 11)
            ->get();

        $states = DB::table('estado_empleado')->selectRaw('id_estado_empleado as value, nombre_estado_empleado as label')->get();
        $typesOfContract = DB::table('tipo_contrato')->selectRaw('id_tipo_contrato as value, nombre_tipo_contrato as label')->get();

        return response()->json([
            'dependencies'          => $dependencies,
            'states'                => $states,
            'typesOfContract'       => $typesOfContract
        ]);
    }
    public function getReportEmployeesRRHH(Request $request)
    {
        $dependencia = Dependencia::find($request->depId);
        $fieldToSearch = '';
        if ($dependencia->id_tipo_dependencia == 1) {
            $fieldToSearch = 1; //Por campo dep_id_dependencia
        } else {
            if ($dependencia->id_tipo_dependencia == 2) {
                $fieldToSearch = 2; //por id_dependencia
            }
        }
        $query = Empleado::with(
            [
                // 'plazas_asignadas.dependencia',
                // 'plazas_asignadas.detalle_plaza',
                'plazas_asignadas' => function ($query) use ($request, $fieldToSearch) {
                    $query->where('estado_plaza_asignada', $request->status)
                        ->whereHas('dependencia', function ($query) use ($request, $fieldToSearch) {
                            if ($fieldToSearch == 1) {
                                $query->where('dep_id_dependencia', $request->depId);
                            }
                            if ($fieldToSearch == 2) {
                                $query->where('id_dependencia', $request->depId);
                            }
                        })
                        ->whereHas('detalle_plaza', function ($query) use ($request) {
                            $query->where('id_tipo_contrato', $request->typeOfContract);
                        });
                }
            ]
        )->where('id_estado_empleado', $request->status)
            ->whereHas(
                'plazas_asignadas.dependencia',
                function ($query) use ($request, $fieldToSearch) {
                    if ($fieldToSearch == 1) {
                        $query->where('dep_id_dependencia', $request->depId);
                    } else {
                        if ($fieldToSearch == 2) {
                            $query->where('id_dependencia', $request->depId);
                        }
                    }
                }
            )
            ->whereHas(
                'plazas_asignadas.detalle_plaza',
                function ($query) use ($request) {
                    $query->where('id_tipo_contrato', $request->typeOfContract);
                }
            )
            ->get();
        return response()->json([
            'query'          => $query,
        ]);
    }
}
