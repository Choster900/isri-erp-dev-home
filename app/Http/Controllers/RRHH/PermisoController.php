<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Http\Requests\RRHH\PermisoRequest;
use App\Models\EtapaPermiso;
use App\Models\Permiso;
use App\Models\PlazaAsignada;
use App\Models\TipoPermiso;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PermisoController extends Controller
{
    public function getJobPermissions(Request $request)
    {
        $columns = [
            'id_permiso',
            'nombre_tipo_permiso',
            'pnombre_persona',
            'fecha_inicio_permiso',
            'horas',
            'id_estado_permiso'
        ];

        $user = $request->user();
        $id_empleado = $user->persona->empleado->id_empleado;

        $arrayIdDependencias = $user->persona->empleado->plazas_asignadas
            ->where('estado_plaza_asignada', 1)
            ->pluck('id_dependencia')
            ->toArray();

        //
        //Ver la forma de limitar para que no pueda ver ni crear permisos de jefes de dependencia  
        //
        
        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = Permiso::select('*')
            ->join('tipo_permiso', 'tipo_permiso.id_tipo_permiso', '=', 'permiso.id_tipo_permiso')
            ->leftJoin('motivo_permiso', 'motivo_permiso.id_motivo_permiso', '=', 'permiso.id_motivo_permiso')
            ->join('empleado', 'empleado.id_empleado', '=', 'permiso.id_empleado')
            ->join('persona', 'persona.id_persona', '=', 'empleado.id_persona')
            ->join('plaza_asignada', 'plaza_asignada.id_plaza_asignada', '=', 'permiso.id_plaza_asignada')
            ->join('dependencia', 'dependencia.id_dependencia', '=', 'plaza_asignada.id_dependencia');
        if ($request->execute != 0) {
            $query->whereIn('plaza_asignada.id_dependencia', $arrayIdDependencias);
        } else {
            $query->where('permiso.id_empleado', $id_empleado);
        }
        if ($column != 4) {
            $query->orderBy($columns[$column], $dir);
        } else {
            $query->orderByRaw(
                '(SELECT 
                    SUM(CASE 
                        WHEN fecha_inicio_permiso IS NOT NULL AND fecha_fin_permiso IS NOT NULL 
                            THEN (DATEDIFF(fecha_fin_permiso, fecha_inicio_permiso) + 1) * 8 * 60 * 60 
                        ELSE TIME_TO_SEC(TIMEDIFF(hora_salida_permiso, hora_entrada_permiso)) 
                    END)
                    FROM permiso as sub_permiso 
                    WHERE sub_permiso.id_permiso = permiso.id_permiso) ' . $dir
            );
        }

        if ($search_value) {
            $query->where(function ($query) use ($search_value) {
                $query->where('id_permiso', 'like', '%' . $search_value['id_permiso'] . '%')
                    ->where('codigo_tipo_permiso', 'like', '%' . $search_value['nombre_tipo_permiso'] . '%')
                    ->where('id_estado_permiso', 'like', '%' . $search_value['id_estado_permiso'] . '%')
                    ->where(function ($query) use ($search_value) {
                        if ($search_value['pnombre_persona']) {
                            $query->whereRaw("MATCH(pnombre_persona, snombre_persona, tnombre_persona, papellido_persona, sapellido_persona, tapellido_persona) AGAINST(?)", $search_value["pnombre_persona"]);
                        }
                    })
                    ->where(function ($query) use ($search_value) {
                        if ($search_value['fecha_inicio_permiso']) {
                            $query->whereRaw('? BETWEEN fecha_inicio_permiso AND IFNULL(fecha_fin_permiso, fecha_inicio_permiso)', $search_value['fecha_inicio_permiso']);
                        }
                    })
                    ->where(function ($query) use ($search_value) {
                        if ($search_value['horas']) {
                            $query->whereRaw(
                                '? = (SELECT 
                                    SUM(CASE 
                                        WHEN fecha_inicio_permiso IS NOT NULL AND fecha_fin_permiso IS NOT NULL 
                                            THEN (DATEDIFF(fecha_fin_permiso, fecha_inicio_permiso) + 1) * 8 * 60 * 60 
                                        ELSE TIME_TO_SEC(TIMEDIFF(hora_salida_permiso, hora_entrada_permiso)) 
                                    END)
                                    FROM permiso as sub_permiso
                                    WHERE sub_permiso.id_permiso = permiso.id_permiso) ',
                                $search_value['horas'] * 60 * 60
                            );
                        }
                    });
            });
        }

        $permissions = $query->paginate($length)->onEachSide(1);
        return ['data' => $permissions, 'draw' => $request->input('draw')];
    }

    public function getDataPermissionModal(Request $request, $idEmpleadoFromView)
    {
        $user = User::find($request->user()->id_usuario);
        $executePermit = $request->ejecutar;

        $arrayIdDependencias = $user->persona->empleado->plazas_asignadas
            ->where('estado_plaza_asignada', 1)
            ->pluck('id_dependencia')
            ->toArray();

        $baseQuery = DB::table('empleado as e')
            ->selectRaw('CONCAT(e.codigo_empleado, "-",CONCAT_WS(" ", p.pnombre_persona, p.snombre_persona, p.tnombre_persona,
                    p.papellido_persona, p.sapellido_persona, p.tapellido_persona)) as label,
                    e.id_empleado as value');

        $baseQuery = $baseQuery->join('persona as p', 'e.id_persona', '=', 'p.id_persona')
            ->join('plaza_asignada as pa', 'e.id_empleado', '=', 'pa.id_empleado')
            ->where('pa.estado_plaza_asignada', 1);

        if ($executePermit != 0) {
            $empleados = $baseQuery->whereIn('pa.id_dependencia', $arrayIdDependencias)
                ->groupBy(
                    'e.id_empleado',
                    'e.codigo_empleado',
                    'p.pnombre_persona',
                    'p.snombre_persona',
                    'p.tnombre_persona',
                    'p.papellido_persona',
                    'p.sapellido_persona',
                    'p.tapellido_persona'
                )
                ->get();
        } else {
            $empleados = $baseQuery->where('e.id_empleado', $user->persona->empleado->id_empleado)
                ->get();
        }

        if ($idEmpleadoFromView != 0 || $executePermit == 0) {
            if ($idEmpleadoFromView == 0) {
                $id_empleado = $user->persona->empleado->id_empleado;
            } else {
                $id_empleado = $idEmpleadoFromView;
            }
            $permissionData = $this->getPermissionData($id_empleado);
        }


        $permissionReason = DB::table('motivo_permiso')->selectRaw('id_motivo_permiso as value, nombre_motivo_permiso as label')
            ->get();

        return response()->json([
            'typesOfPermissions'          => $permissionData['typesOfPermissions'] ?? [],
            'jobPositions'                => $permissionData['jobPositions'] ?? [],
            'employees'                   => $empleados ?? [],
            'permissionReasons'           => $permissionReason ?? []
        ]);
    }

    public function getPermissionData($id_empleado)
    {
        $typesOfPermissions = DB::table('tipo_permiso as tpe')
            ->leftJoin(
                DB::raw('
                    (
                        SELECT
                            tp.id_tipo_permiso,
                            tp.nombre_tipo_permiso,
                            (tp.tiempo_max_tipo_permiso * 60 * 60) as limite_permiso,
                            SUM(
                                CASE
                                    WHEN p.fecha_inicio_permiso IS NOT NULL AND p.fecha_fin_permiso IS NOT NULL THEN 
                                        (DATEDIFF(p.fecha_fin_permiso, p.fecha_inicio_permiso) + 1) * 8 * 60 * 60
                                    ELSE
                                        TIME_TO_SEC(TIMEDIFF(p.hora_salida_permiso, p.hora_entrada_permiso))
                                END
                            ) AS total_permiso_acumulado
                        FROM permiso p
                        INNER JOIN tipo_permiso tp ON p.id_tipo_permiso = tp.id_tipo_permiso
                        WHERE p.id_estado_permiso IN (1,2,3)
                        AND p.id_empleado = ?
                        AND YEAR(p.fecha_inicio_permiso) = YEAR(CURDATE())
                        GROUP BY tp.id_tipo_permiso, tp.nombre_tipo_permiso, tp.tiempo_max_tipo_permiso
                    ) AS t'),
                'tpe.id_tipo_permiso',
                '=',
                't.id_tipo_permiso'
            )
            ->select(
                'tpe.id_tipo_permiso as value',
                'tpe.nombre_tipo_permiso as label',
                DB::raw('SEC_TO_TIME(tpe.tiempo_max_tipo_permiso * 60 * 60) as limite_permiso'),
                DB::raw('SEC_TO_TIME(t.total_permiso_acumulado) as total_acumulado'),
                DB::raw('SEC_TO_TIME((tpe.tiempo_max_tipo_permiso * 60 * 60) - IFNULL(t.total_permiso_acumulado,0)) as restante')
            )
            ->addBinding($id_empleado)
            ->get();


        $jobPositions = PlazaAsignada::selectRaw('plaza_asignada.id_plaza_asignada as value, plaza.nombre_plaza as label')
            ->join('detalle_plaza', 'plaza_asignada.id_det_plaza', '=', 'detalle_plaza.id_det_plaza')
            ->join('plaza', 'detalle_plaza.id_plaza', '=', 'plaza.id_plaza')
            ->where('plaza_asignada.id_empleado', $id_empleado)
            ->get();

        return [
            'typesOfPermissions' => $typesOfPermissions ?? [],
            'jobPositions' => $jobPositions ?? [],
        ];
    }

    public function storeEmployeePermission(PermisoRequest $request)
    {
        $isValidTime = true;
        if ($request->typeOfPermissionId == 6) { //No marcacion
            $fechaPermiso = Carbon::parse($request->startDate)->format('Y-m-d');
            $mesPermiso = Carbon::createFromFormat('Y-m-d', $fechaPermiso)->format('m');
            $mesNombre = Carbon::createFromFormat('m', $mesPermiso)->locale('es_ES')->monthName;

            $cantidadPermisos = DB::table('permiso')
                ->where('id_tipo_permiso', 6)
                ->whereIn('id_estado_permiso', [1, 2, 3]) // Check if id_estado_permiso is 1 or 2
                ->where('id_empleado', $request->employeeId)
                ->whereMonth('fecha_inicio_permiso', $mesPermiso)
                ->count();
            if ($cantidadPermisos >= 3) {
                return response()->json([
                    'logical_error' => 'Ya ha agotado la cantidad de permisos (3) por olvido de marcación del mes de ' . $mesNombre,
                    'refresh' => false
                ], 422);
            }
        } else {
            if ($request->typeOfPermissionId != 5) { //Si no es Mision oficial
                $dateTimeField = ($request->periodOfTime == 1) ? 'time' : 'date';
                $isValidTime = $this->validateTime(
                    ($request->periodOfTime == 1) ? $request->startTime : $request->startDate,
                    ($request->periodOfTime == 1) ? $request->endTime : $request->endDate,
                    $request->typeOfPermissionId,
                    $request->employeeId,
                    $request->permissionId,
                    $dateTimeField
                );
            }
        }

        if ($isValidTime) {
            $startDateFormatted = Carbon::parse($request->startDate)->format('Y-m-d');
            $endDateFormatted = $request->endDate ? Carbon::parse($request->endDate)->format('Y-m-d') : null;
            $startTimeFormatted = $request->startTime ? Carbon::parse($request->startTime)->format('H:i:s') : null;
            $endTimeFormatted = $request->endTime ? Carbon::parse($request->endTime)->format('H:i:s') : null;

            $permission = new Permiso([
                'id_empleado'                 => $request->employeeId,
                'id_plaza_asignada'           => $request->jobPositionId,
                'id_motivo_permiso'           => $request->typeOfPermissionId == 6 ? $request->permissionReasonId : null,
                'id_tipo_permiso'             => $request->typeOfPermissionId,
                'id_estado_permiso'           => 1,
                'fecha_inicio_permiso'        => $startDateFormatted,
                'fecha_fin_permiso'           => $request->periodOfTime == 2 ? $endDateFormatted : null,
                'destino_permiso'             => $request->typeOfPermissionId == 5 ? $request->destination : null,
                'comentarios_permiso'         => $request->observation,
                'hora_entrada_permiso'        => $startTimeFormatted,
                'hora_salida_permiso'         => $endTimeFormatted,
                'retornar_empleado_permiso'   => ($request->typeOfPermissionId == 5 && $request->periodOfTime == 1) ? $request->comingBack : null,
                'estado_permiso'              => 1,
                'fecha_reg_permiso'           => Carbon::now(),
                'usuario_permiso'             => $request->user()->nick_usuario,
                'ip_permiso'                  => $request->ip(),
            ]);
            $permission->save();

            return response()->json([
                'mensaje'          => "Permiso guardado con exito",
            ]);
        } else {
            return response()->json([
                'logical_error' => 'El tiempo seleccionado excede el tiempo restante para el permiso seleccionado.',
                'refresh' => true
            ], 422);
        }
    }
    public function validateTime($start, $end, $typeOfPermissionId, $employeeId, $permissionId, $type)
    {
        if ($type == 'time') {
            $startFormatted = strlen($start) === 5 ? Carbon::createFromFormat('H:i', $start) : Carbon::createFromFormat('H:i:s', $start);
            $endFormatted = strlen($end) === 5 ? Carbon::createFromFormat('H:i', $end) : Carbon::createFromFormat('H:i:s', $end);

            $timeDifferenceInMinutesFromView = $endFormatted->diffInMinutes($startFormatted);
        } else {
            $startFormatted = Carbon::parse($start);
            $endFormatted = Carbon::parse($end);
            $timeDifferenceInMinutesFromView = (($endFormatted->diffInDays($startFormatted)) + 1) * 8 * 60;
        }

        if($permissionId != ''){
            $permissionTimeInSeconds = DB::table('permiso')
            ->selectRaw('SUM(CASE WHEN fecha_inicio_permiso IS NOT NULL AND fecha_fin_permiso IS NOT NULL THEN (DATEDIFF(fecha_fin_permiso, fecha_inicio_permiso) + 1) * 8 * 60 * 60 ELSE TIME_TO_SEC(TIMEDIFF(hora_salida_permiso, hora_entrada_permiso)) END) AS total_permiso_acumulado')
            ->where('id_permiso', $permissionId)
            ->value('total_permiso_acumulado');
        }else{
            $permissionTimeInSeconds = 0;
        }

        $totalPermissionsInSecondsFromDB = DB::table('permiso')
            ->selectRaw('SUM(CASE WHEN fecha_inicio_permiso IS NOT NULL AND fecha_fin_permiso IS NOT NULL THEN (DATEDIFF(fecha_fin_permiso, fecha_inicio_permiso) + 1) * 8 * 60 * 60 ELSE TIME_TO_SEC(TIMEDIFF(hora_salida_permiso, hora_entrada_permiso)) END) AS total_permiso_acumulado')
            ->where('id_empleado', $employeeId)
            ->where('id_estado_permiso', 1)
            ->where('id_tipo_permiso', $typeOfPermissionId)
            ->value('total_permiso_acumulado');

        $permissionLimit = TipoPermiso::find($typeOfPermissionId);

        $totalPermissionsInMinutesFromDB = floor(($totalPermissionsInSecondsFromDB - $permissionTimeInSeconds) / 60);

        $availableTimeInMinutes = ($permissionLimit->tiempo_max_tipo_permiso * 60) - $totalPermissionsInMinutesFromDB;

        if ($timeDifferenceInMinutesFromView > $availableTimeInMinutes) {
            return false;
        } else {
            return true;
        }
    }
    public function updateEmployeePermission(PermisoRequest $request)
    {
        $permission = Permiso::findOrFail($request->permissionId);
        if ($permission->id_estado_permiso != 1) {
            return response()->json([
                'logical_error' => 'El permiso ha sido aprobado, denegado o eliminado. Consulta con tu jefe inmediato si deseas modificarlo.',
                'close' => true
            ], 422);
        } else {
            $isValidTime = true;
            if ($request->typeOfPermissionId == 6) {
                $fechaPermiso = Carbon::parse($request->startDate)->format('Y-m-d');
                $mesPermiso = Carbon::createFromFormat('Y-m-d', $fechaPermiso)->format('m');
                $mesNombre = Carbon::createFromFormat('m', $mesPermiso)->locale('es_ES')->monthName;

                $cantidadPermisos = DB::table('permiso')
                    ->where('id_permiso', '!=', $request->permissionId)
                    ->whereIn('id_estado_permiso', [1, 2, 3]) // Check if id_estado_permiso is 1 or 2
                    ->where('id_tipo_permiso', 6)
                    ->where('id_empleado', $request->employeeId)
                    ->whereMonth('fecha_inicio_permiso', $mesPermiso)
                    ->count();
                if ($cantidadPermisos >= 3) {
                    return response()->json([
                        'logical_error' => 'Ya ha agotado la cantidad de permisos (3) por olvido de marcación del mes de ' . $mesNombre,
                        'refresh' => false
                    ], 422);
                }
            } else {
                if ($request->typeOfPermissionId != 5) {
                    $dateTimeField = ($request->periodOfTime == 1) ? 'time' : 'date';
                    $isValidTime = $this->validateTime(
                        ($request->periodOfTime == 1) ? $request->startTime : $request->startDate,
                        ($request->periodOfTime == 1) ? $request->endTime : $request->endDate,
                        $request->typeOfPermissionId,
                        $request->employeeId,
                        $request->permissionId ?? '',
                        $dateTimeField
                    );
                }
            }

            if ($isValidTime) {
                $startDateFormatted = Carbon::parse($request->startDate)->format('Y-m-d');
                $endDateFormatted = $request->endDate ? Carbon::parse($request->endDate)->format('Y-m-d') : null;
                $startTimeFormatted = $request->startTime ? Carbon::parse($request->startTime)->format('H:i:s') : null;
                $endTimeFormatted = $request->endTime ? Carbon::parse($request->endTime)->format('H:i:s') : null;

                $data = [
                    'id_empleado'                 => $request->employeeId,
                    'id_plaza_asignada'           => $request->jobPositionId,
                    'id_motivo_permiso'           => $request->typeOfPermissionId == 6 ? $request->permissionReasonId : null,
                    'id_tipo_permiso'             => $request->typeOfPermissionId,
                    'id_estado_permiso'           => 1,
                    'fecha_inicio_permiso'        => $startDateFormatted,
                    'fecha_fin_permiso'           => $request->periodOfTime == 2 ? $endDateFormatted : null,
                    'destino_permiso'             => $request->typeOfPermissionId == 5 ? $request->destination : null,
                    'comentarios_permiso'         => $request->observation,
                    'hora_entrada_permiso'        => $startTimeFormatted,
                    'hora_salida_permiso'         => $endTimeFormatted,
                    'retornar_empleado_permiso'   => ($request->typeOfPermissionId == 5 && $request->periodOfTime == 1) ? $request->comingBack : null,
                    'estado_permiso'              => 1,
                    'fecha_mod_permiso'           => Carbon::now(),
                    'usuario_permiso'             => $request->user()->nick_usuario,
                    'ip_permiso'                  => $request->ip(),
                ];

                $permission->update($data);

                return response()->json([
                    'mensaje'          => "Permiso actualizado con exito",
                ]);
            } else {
                return response()->json([
                    'logical_error' => 'El tiempo seleccionado excede el tiempo restante para el permiso seleccionado.',
                    'refresh' => true
                ], 422);
            }
        }
    }
    public function getPermissionInfoById(Request $request)
    {
        $permiso = Permiso::where('id_permiso', $request->id_permiso)
            ->with(['empleado.persona', 'plaza_asignada.centro_atencion', 'plaza_asignada.dependencia', 'tipo_permiso', 'motivo_permiso', 'plaza_asignada.detalle_plaza.plaza', 'empleado.titulo_profesional', 'etapa_permiso'])
            ->first();

        if (!$permiso || $permiso->estado_permiso == 0) {
            return response()->json([
                'logical_error' => 'El permiso seleccionado ha sido desactivado o no existe.',
            ], 422);
        } else {
            if ($permiso->id_estado_permiso != $request->id_estado_permiso) {
                return response()->json([
                    'logical_error' => 'El permiso seleccionado ha cambiado de estado, intente nuevamente.',
                ], 422);
            } else {
                $fechaPermiso = Carbon::parse($permiso->fecha_inicio_permiso)->format('m');
                //Get the permission stages
                $permissionStages = EtapaPermiso::with('empleado.persona','persona_etapa','estado_etapa_permiso_rel','empleado.titulo_profesional')
                    ->where('id_permiso',$permiso->id_permiso)
                    ->orderBy('id_etapa_permiso','asc')
                    ->get();
                //Special validation for no entry or exit marking
                $cantidadPermisos = DB::table('permiso')
                    ->where('id_tipo_permiso', 6)
                    ->whereIn('id_estado_permiso', [1, 2, 3]) // Check if id_estado_permiso is 1 or 2
                    ->where('id_empleado', $permiso->id_empleado)
                    ->whereMonth('fecha_inicio_permiso', $fechaPermiso)
                    //->where('fecha_inicio_permiso', '<=', $permiso->fecha_inicio_permiso)
                    ->count();
                return response()->json([
                    'permiso' => $permiso,
                    'limite'  => $cantidadPermisos,
                    'etapas'  => $permissionStages,
                ]);
            }
        }
    }

    public function sendPermission(Request $request)
    {
        $permiso = Permiso::find($request->id);
        $id_rol = session()->get('id_rol');

        $existStage = EtapaPermiso::where('id_permiso', $permiso->id_permiso)
            ->first();

        if (!$existStage && $permiso) {
            DB::beginTransaction();
            try {
                $permissionStage = new EtapaPermiso([
                    'id_empleado'                   => $permiso->empleado->persona->id_persona,
                    'id_permiso'                    => $permiso->id_permiso,
                    'id_estado_etapa_permiso'       => 1,
                    'id_persona_etapa'              => 1,
                    // 'id_estado_etapa_permiso'       => $id_rol == 14 ? 2 : ($id_rol == 15 ? 4 : 1),
                    // 'id_persona_etapa'              => $id_rol == 14 ? 2 : ($id_rol == 15 ? 3 : 1),
                    'fecha_reg_etapa_permiso'       => Carbon::now(),
                    'usuario_etapa_permiso'         => $request->user()->nick_usuario,
                    'ip_etapa_permiso'              => $request->ip(),
                    'estado_etapa_permiso'          => 1,
                ]);
                $permissionStage->save();

                $data = [
                    'id_estado_permiso'           => 2,
                    'id_tipo_flujo_control'       => $request->tipo_flujo,
                    'fecha_mod_permiso'           => Carbon::now(),
                    'usuario_permiso'             => $request->user()->nick_usuario,
                    'ip_permiso'                  => $request->ip(),
                ];
                $permiso->update($data);

                DB::commit(); // Confirma las operaciones en la base de datos
                return response()->json([
                    'mensaje'          => "Permiso enviado con exito",
                ]);
            } catch (\Exception $e) {
                DB::rollBack(); // En caso de error, revierte las operaciones anteriores
                return response()->json([
                    'logical_error' => 'Ha ocurrido un error con sus datos.',
                    'error' => $e,
                ], 422);
            }
        } else {
            return response()->json([
                'logical_error' => 'El permiso ya ha sido registrado.',
            ], 422);
        }
    }

    public function deletePermission(Request $request)
    {
        $permission = Permiso::find($request->id);
        if ($permission->id_estado_permiso == $request->status) {
            $data = [
                'id_estado_permiso'           => 5,
                'fecha_mod_permiso'           => Carbon::now(),
                'usuario_permiso'             => $request->user()->nick_usuario,
                'ip_permiso'                  => $request->ip(),
            ];
            $permission->update($data);
            return response()->json([
                'mensaje'          => "Permiso eliminado con exito.",
            ]);
        } else {
            return response()->json([
                'logical_error' => 'El permiso seleccionado ha cambiado de estado.',
            ], 422);
        }
    }
}
