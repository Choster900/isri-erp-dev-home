<?php

namespace App\Http\Controllers\RRHH;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CentroAtencion;
use App\Models\Dependencia;
use App\Models\Empleado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ReporteRRHHController extends Controller
{
    public function getInfoForReports(Request $request)
    {
        $dependencies = Dependencia::selectRaw("id_dependencia as value, concat(nombre_dependencia,' (',codigo_dependencia,')') as label, id_centro_atencion, codigo_dependencia")
            ->where('estado_dependencia', 1)->get();
        $mainCenters = CentroAtencion::selectRaw("id_centro_atencion as value, concat(nombre_centro_atencion,' (',codigo_centro_atencion,' )') as label, codigo_centro_atencion")
            ->where('estado_centro_atencion', 1)->get();
        // Agregar el elemento al inicio de la colección
        $mainCenters->prepend(['value' => 0, 'label' => 'TODOS LOS CENTROS']);

        $states = DB::table('estado_empleado')->selectRaw('id_estado_empleado as value, nombre_estado_empleado as label')->get();
        $typesOfContract = DB::table('tipo_contrato')->selectRaw('id_tipo_contrato as value, nombre_tipo_contrato as label')->get();

        return response()->json([
            'dependencies'          => $dependencies,
            'states'                => $states,
            'typesOfContract'       => $typesOfContract,
            'mainCenters'           => $mainCenters
        ]);
    }
    public function getReportEmployeesRRHH(Request $request)
    {
        $customMessages = [
            'parentId.required' => 'Debe seleccionar el centro de atención.',
        ];
        $validatedData = Validator::make($request->all(), [
            'parentId' => 'required',
        ], $customMessages)->validate();

        $dependenciasIds = [];
        if ($request->depId) {
            $dependencia = Dependencia::find($request->depId);
            $dependenciasIds = $dependencia->all_dependencias_inferiores()->pluck('id_dependencia')->prepend($request->depId);
        }
        $query = Empleado::with(
            [
                'plazas_asignadas.dependencia',
                'plazas_asignadas.detalle_plaza',
                'plazas_asignadas'
                => function ($query) use ($request, $dependenciasIds) {
                    //Filtramos si existe status desde la vista
                    if ($request->status) {
                        if ($request->parentId != 0) {
                            $query->where('estado_plaza_asignada', $request->status == 1 ? 1 : 0);
                        }
                    }
                    //Filtramos por fechas
                    if ($request->startDate) {
                        $startDate = Carbon::parse($request->startDate)->endOfDay();
                        if ($request->status == 2) { //Inactivo
                            $query->whereDate('fecha_renuncia_plaza_asignada', '<=', $startDate);
                        } else {
                            if ($request->status == 1) { //Activo
                                $query->whereDate('fecha_plaza_asignada', '<=', $startDate);
                            } else {
                                $query->whereDate('fecha_renuncia_plaza_asignada', '<=', $startDate)
                                    ->orWhereDate('fecha_plaza_asignada', '<=', $startDate);
                            }
                        }
                    }
                    //Filtramos si existe tipo contratacion desde la vista
                    if ($request->typeOfContract) {
                        $query->whereHas('detalle_plaza', function ($query) use ($request) {
                            $query->where('id_tipo_contrato', $request->typeOfContract);
                        });
                    }
                    //Filtramos por centro o por dependencia
                    if ($request->parentId != 0) { //Verificamos si la opcion es 'Todos los centros'
                        $query->whereHas('dependencia', function ($query) use ($request, $dependenciasIds) {
                            if ($request->depId) {
                                $query->whereIn('id_dependencia', $dependenciasIds);
                            } else {
                                $query->where('id_centro_atencion', $request->parentId);
                            }
                        });
                    }
                }
            ]
        );

        //Filtramos por centro o por dependencia
        if ($request->parentId != 0) { //Verificamos si la opcion NO es 'Todos los centros'
            $query->whereHas(
                'plazas_asignadas.dependencia',
                function ($query) use ($request, $dependenciasIds) {
                    if ($request->depId) {
                        $query->whereIn('id_dependencia', $dependenciasIds);
                    } else {
                        $query->where('id_centro_atencion', $request->parentId);
                    }
                }
            );
        }
        //Filtramos si existe status desde la vista
        if ($request->status) {
            if ($request->parentId == 0) {
                $query->where('id_estado_empleado', $request->status);
            } else {
                $query->whereHas(
                    'plazas_asignadas',
                    function ($query) use ($request) {
                        $query->where('estado_plaza_asignada', $request->status == 1 ? 1 : 0);
                    }
                );
            }
        }
        //Filtramos por fecha
        if ($request->startDate) {
            $query->whereHas(
                'plazas_asignadas',
                function ($query) use ($request) {
                    $startDate = Carbon::parse($request->startDate)->endOfDay();
                    if ($request->status == 2) { //Inactivo
                        $query->whereDate('fecha_renuncia_plaza_asignada', '<=', $startDate);
                    } else {
                        if ($request->status == 1) { //Activo
                            $query->whereDate('fecha_plaza_asignada', '<=', $startDate);
                        } else {
                            $query->whereDate('fecha_renuncia_plaza_asignada', '<=', $startDate)
                                ->orWhereDate('fecha_plaza_asignada', '<=', $startDate);
                        }
                    }
                }
            );
        }
        //Filtramos si existe tipo contratacion desde la vista
        if ($request->typeOfContract) {
            $query->whereHas(
                'plazas_asignadas.detalle_plaza',
                function ($query) use ($request) {
                    $query->where('id_tipo_contrato', $request->typeOfContract);
                }
            );
        }

        return response()->json([
            'query'          => $query->get(),
        ]);
    }
}
