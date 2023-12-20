<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\Dependencia;
use App\Models\EtapaPermiso;
use App\Models\Permiso;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JefeInmediatoController extends Controller
{
    public function getReqJefeInmediato(Request $request)
    {
        $columns = [
            'id_permiso',
            'nombre_tipo_permiso',
            'pnombre_persona',
            'fecha_inicio_permiso',
            'horas',
            'id_estado_permiso'
        ];
        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $user = $request->user();
        $id_persona = $user->persona->id_persona;

        $idsEmpleados = [];

        //Obtiene los empleados destacados en las dependencias en las cual es jefe
        $deps = Dependencia::with('plazas_asignadas.empleado')->where('id_persona', $id_persona)
        ->where('estado_dependencia', 1)->get();
        foreach($deps as $dep){
            foreach($dep->plazas_asignadas as $plaza){
                if($plaza->estado_plaza_asignada == 1){
                    $idsEmpleados[] = $plaza->empleado->id_empleado;
                }
            }
        }
        
        //Obtiene los jefes de las dependencias hijas
        $depIds = Dependencia::where('id_persona', $id_persona)
            ->where('estado_dependencia', 1)
            ->pluck('id_dependencia')
            ->toArray();
        $dependencies = Dependencia::with('jefatura.empleado')->whereIn('jerarquia_organizacion_dependencia', $depIds)->get();
        foreach ($dependencies as $dep) {
            if ($dep->jefatura && $dep->estado_dependencia == 1) {
                $idsEmpleados[] = $dep->jefatura->empleado->id_empleado;
            }
        }

        $query = Permiso::select('*')
            ->with([
                'tipo_permiso',
                'motivo_permiso',
                'empleado.persona',
                'plaza_asignada.dependencia',
                'etapa_permiso'
            ])
            ->whereHas('empleado.persona', function ($query) use ($id_persona) {
                $query->where('id_persona', '!=', $id_persona);
            })
            ->whereHas('etapa_permiso', function ($query) {
                $query
                    ->where('id_persona_etapa', 1)
                    ->where('id_estado_etapa_permiso', 1);
            })
            ->whereIn('id_empleado',$idsEmpleados);

        if ($column == -1) {
            $query->orderByRaw('(
                    SELECT id_etapa_permiso
                    FROM etapa_permiso
                    WHERE etapa_permiso.id_permiso = permiso.id_permiso
                    ORDER BY id_etapa_permiso DESC
                    LIMIT 1
                ) DESC');
        }


        $permissions = $query->paginate($length)->onEachSide(1);
        return ['data' => $permissions, 'draw' => $request->input('draw'), 'res' => $dependencies, 'emp'=>$idsEmpleados] ;
    }

    public function setSupervisorApproval(Request $request)
    {
        $permiso = Permiso::find($request->id);
        $idRol = $request->id_rol;
        $user = $request->user();

        if ($idRol == 14) {
            DB::beginTransaction();
            try {
                //Desactivamos la etapa anterior
                $oldStage = EtapaPermiso::where('id_permiso', $permiso->id_permiso)
                    ->where('estado_etapa_permiso', 1)
                    ->first();
                if ($oldStage) {
                    $oldStage->estado_etapa_permiso = 0;
                    $oldStage->update();
                }

                $permissionStage = new EtapaPermiso([
                    'id_empleado'                   => $user->persona->empleado->id_empleado,
                    'id_permiso'                    => $permiso->id_permiso,
                    'id_estado_etapa_permiso'       => 2,
                    'id_persona_etapa'              => 2,
                    'fecha_reg_etapa_permiso'       => Carbon::now(),
                    'usuario_etapa_permiso'         => $request->user()->nick_usuario,
                    'ip_etapa_permiso'              => $request->ip(),
                    'estado_etapa_permiso'          => 1,
                ]);
                $permissionStage->save();

                //Si es el flujo corto de admon se le cambia el estado al permiso
                if ($permiso->id_tipo_flujo_control == 1) {
                    $data = [
                        'id_estado_permiso'           => 3,
                        'fecha_mod_permiso'           => Carbon::now(),
                        'usuario_permiso'             => $request->user()->nick_usuario,
                        'ip_permiso'                  => $request->ip(),
                    ];
                    $permiso->update($data);
                    //Se desactiva la etapa ya que el flujo ha finalizado
                    // $permissionStage->estado_etapa_permiso = 0;
                    // $permissionStage->update();
                }

                DB::commit(); // Confirma las operaciones en la base de datos
                return response()->json([
                    'mensaje'          => "Permiso aprobado con exito.",
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
                'logical_error' => 'No puedes aprobar el permiso seleccionado.',
            ], 422);
        }
    }

    public function setSupervisorDenial(Request $request)
    {
        $permiso = Permiso::find($request->id);
        $idRol = $request->id_rol;
        $user = $request->user();

        if ($idRol == 14) {
            DB::beginTransaction();
            try {
                $permissionStage = new EtapaPermiso([
                    'id_empleado'                   => $user->persona->empleado->id_empleado,
                    'id_permiso'                    => $permiso->id_permiso,
                    'observacion_etapa_permiso'     => $request->comment,
                    'id_estado_etapa_permiso'       => 3,
                    'id_persona_etapa'              => 2,
                    'fecha_reg_etapa_permiso'       => Carbon::now(),
                    'usuario_etapa_permiso'         => $request->user()->nick_usuario,
                    'ip_etapa_permiso'              => $request->ip(),
                    'estado_etapa_permiso'          => 1,
                ]);
                $permissionStage->save();

                //Desactivamos la etapa anterior
                // $oldStage = EtapaPermiso::where('id_permiso', $permiso->id_permiso)
                //     ->where('estado_etapa_permiso', 1)
                //     ->first();
                // if ($oldStage) {
                //     $oldStage->estado_etapa_permiso = 0;
                //     $oldStage->update();
                // }

                $data = [
                    'id_estado_permiso'           => 4,
                    'fecha_mod_permiso'           => Carbon::now(),
                    'usuario_permiso'             => $request->user()->nick_usuario,
                    'ip_permiso'                  => $request->ip(),
                ];
                $permiso->update($data);

                DB::commit(); // Confirma las operaciones en la base de datos
                return response()->json([
                    'mensaje'          => "Permiso denegado con exito.",
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
                'logical_error' => 'No puedes rechazar el permiso seleccionado.',
            ], 422);
        }
    }
}
