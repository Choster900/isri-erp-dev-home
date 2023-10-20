<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\Dependencia;
use App\Models\EtapaPermiso;
use App\Models\FlujoControl;
use Illuminate\Support\Facades\DB;

use App\Models\Permiso;

use Illuminate\Http\Request;

class SolicitudPermisoController extends Controller
{
    public function getPermissionRequests(Request $request)
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

        $dependenciaJefe = Dependencia::where('id_persona', $id_persona)->first();

        $query = Permiso::select('*')
            ->join('tipo_permiso', 'tipo_permiso.id_tipo_permiso', '=', 'permiso.id_tipo_permiso')
            ->leftJoin('motivo_permiso', 'motivo_permiso.id_motivo_permiso', '=', 'permiso.id_motivo_permiso')
            ->join('empleado', 'empleado.id_empleado', '=', 'permiso.id_empleado')
            ->join('persona', 'persona.id_persona', '=', 'empleado.id_persona')
            ->join('plaza_asignada', 'plaza_asignada.id_plaza_asignada', '=', 'permiso.id_plaza_asignada')
            ->join('dependencia', 'dependencia.id_dependencia', '=', 'plaza_asignada.id_dependencia')
            ->join('etapa_permiso', 'permiso.id_permiso', '=', 'etapa_permiso.id_permiso')
            ->where('plaza_asignada.id_empleado', '!=', $user->persona->id_persona)
            ->where(function ($query) use ($user, $dependenciaJefe) {
                $query->where(function ($query) use ($dependenciaJefe) {
                    $query->where('dependencia.jerarquia_organizacion_dependencia', $dependenciaJefe->jerarquia_organizacion_dependencia + 1)
                        ->where('dependencia.dep_id_dependencia', $dependenciaJefe->dep_id_dependencia);
                })->orWhere('dependencia.id_persona', $user->persona->id_persona);
            });

        // $query = Permiso::select('*')
        //     ->join('etapa_permiso', 'permiso.id_permiso', '=', 'etapa_permiso.id_permiso')
        //     ->join('tipo_permiso', 'tipo_permiso.id_tipo_permiso', '=', 'permiso.id_tipo_permiso')
        //     ->leftJoin('motivo_permiso', 'motivo_permiso.id_motivo_permiso', '=', 'permiso.id_motivo_permiso')
        //     ->join('empleado', 'empleado.id_empleado', '=', 'permiso.id_empleado')
        //     ->join('persona', 'persona.id_persona', '=', 'empleado.id_persona')
        //     ->join('plaza_asignada', 'plaza_asignada.id_plaza_asignada', '=', 'permiso.id_plaza_asignada')
        //     ->join('dependencia', 'dependencia.id_dependencia', '=', 'plaza_asignada.id_dependencia')
        //     ->where('dependencia.id_persona', $user->persona->id_persona)
        //     ->orWhere('dependencia.jerarquia_organizacion_dependencia','>',$dependenciaJefe->jerarquia_organizacion_dependencia)
        //     ->where('dependencia.dep_id_dependencia', $dependenciaJefe->dep_id_dependencia)
        //     ->where('plaza_asignada.id_empleado','!=', $user->persona->empleado->id_empleado);

        $permissions = $query->paginate($length)->onEachSide(1);
        return ['data' => $permissions, 'draw' => $request->input('draw')];
    }

    public function sendPermission(Request $request)
    {
        $id_tipo_flujo = $request->tipo_flujo;
        $permiso = Permiso::find($request->id);

        $existStage = EtapaPermiso::where('id_permiso', $permiso->id_permiso)
            ->first();

        if (!$existStage) {
            $count = DB::table('flujo_control')
                ->where('id_tipo_flujo_control', $id_tipo_flujo)
                ->count();
            
            $depToSwitch = '';

            for ($i = 1; $i <= $count; $i++) {
                $idPersonaACargo = '';
                if ($permiso->empleado->persona->id_persona == $permiso->plaza_asignada->dependencia->id_persona) {
                    $idDep = '';
                    if ($i == 1) {
                        $idDep = $permiso->plaza_asignada->dependencia->id_dependencia;
                        $depToSwitch = $idDep;
                    } else {
                        if ($i == 2) {
                            $idDep = $permiso->plaza_asignada->dependencia->jerarquia_organizacion_dependencia;
                            $depToSwitch = $idDep;
                        } else {
                            if($i == 3){
                                $idDep = $permiso->plaza_asignada->dependencia->jerarquia_organizacion_dependencia;
                                $depToSwitch = $idDep;
                            }else{
                                if($i == 4){
                                    
                                }
                            }
                        }
                    }
                    $dependenciaSuperior = Dependencia::find($idDep);
                    $idPersonaACargo = $dependenciaSuperior->id_persona;
                } else {
                    $idPersona = '';
                    if ($i == 1) {
                        $idPersona = $permiso->empleado->persona->id_persona;
                    } else {
                        if ($i == 2) {
                            $idPersona = $permiso->plaza_asignada->dependencia->id_persona;
                        } else {
                            $depSup = Dependencia::find($permiso->plaza_asignada->dependencia->jerarquia_organizacion_dependencia);
                            $idPersona = $depSup->id_persona;
                        }
                    }
                    $idPersonaACargo = $idPersona;
                }

                $idPersonaEtapa = DB::table('flujo_control')
                    ->select('id_persona_etapa')
                    ->where('id_tipo_flujo_control', $id_tipo_flujo)
                    ->where('orden_flujo_control', $i)
                    ->first();
                if ($idPersonaEtapa) {
                    $idPersonaEtapa = $idPersonaEtapa->id_persona_etapa;
                }

                $permissionStage = new EtapaPermiso([
                    'id_empleado'                   => $idPersonaACargo,
                    'id_permiso'                    => $permiso->id_permiso,
                    'id_estado_permiso'             => $i == 1 ? 3 : null,
                    'id_persona_etapa'              => $idPersonaEtapa,
                    'usuario_etapa_permiso'         => $request->user()->nick_usuario,
                    'ip_etapa_permiso'              => $request->ip(),
                    'estado_etapa_permiso'          => $i == 2 ? 1 : 0,
                ]);
                $permissionStage->save();
            }
        } else {
            return response()->json([
                'logical_error' => 'El permiso seleccionado ya ha sido enviado.',
            ], 422);
        }

        $existStage2 = EtapaPermiso::where('id_permiso', $permiso->id_permiso)
            ->first();
        if ($existStage2) {
            return response()->json([
                'mensaje'          => "Permiso enviado con exito",
            ]);
        } else {
            return response()->json([
                'logical_error' => 'Ha ocurrido un error.',
            ], 422);
        }
    }
    public function switchDependency($dependencyId)
    {
        $depency = Dependencia::find($dependencyId);
        return $depency->id_dependencia;
    }
}
